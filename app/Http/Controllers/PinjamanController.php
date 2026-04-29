<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Pengembalian;
use App\Models\Anggota;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pinjaman::with(['pengembalian', 'anggota', 'buku'])
            ->orderBy('created_at', 'desc');

        if ($request->has('kelas') && !empty($request->kelas) && $request->kelas !== 'Semua Kelas') {
            $query->where(function ($q) use ($request) {
                $q->whereHas('anggota', function ($sub) use ($request) {
                    $sub->where('kelas', $request->kelas);
                })->orWhere('kelas', $request->kelas);
            });
        }

        if ($request->has('status') && !empty($request->status) && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->paginate(15);

        $kelasList = Anggota::select('kelas')->distinct()->pluck('kelas')
            ->merge(
                Pinjaman::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas')
            )->unique()->sort()->values();

        return view('pinjamans.index', compact('pinjamans', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        $books = Book::where('stok', '>', 0)->orderBy('judul')->get();
        $bookTitles = Book::pluck('judul')->toArray();
        
        return view('pinjamans.create', compact('anggota', 'books', 'bookTitles'));
    }

    /**
     * Check if book exists in database (AJAX)
     */
    public function checkBook(Request $request)
    {
        try {
            $judul = $request->get('judul');
            
            if (!$judul || trim($judul) == '') {
                return response()->json([
                    'exists' => false,
                    'message' => 'Judul buku tidak boleh kosong'
                ]);
            }
            
            $buku = Book::where('judul', 'LIKE', '%' . $judul . '%')->first();
            
            if ($buku) {
                return response()->json([
                    'exists' => true,
                    'stok' => $buku->stok,
                    'id' => $buku->id,
                    'judul' => $buku->judul,
                    'penulis' => $buku->penulis,
                    'message' => 'Buku ditemukan'
                ]);
            }
            
            return response()->json([
                'exists' => false,
                'message' => 'Buku "' . $judul . '" tidak ditemukan di database perpustakaan. Silakan cek judul atau tambahkan buku terlebih dahulu.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cek ketersediaan stok buku
     */
    private function cekStokBuku($judulBuku)
    {
        $buku = Book::where('judul', 'LIKE', '%' . $judulBuku . '%')->first();
        
        if (!$buku) {
            return ['status' => false, 'message' => 'Buku tidak ditemukan di database perpustakaan.'];
        }
        
        if ($buku->stok <= 0) {
            return ['status' => false, 'message' => 'Stok buku habis!'];
        }
        
        return ['status' => true, 'buku' => $buku];
    }

    /**
     * Store a newly created resource in storage.
     * 🔥 PERBAIKAN: Diperbarui untuk menangani array judul_buku (Banyak buku sekaligus)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kelas'           => 'required|string|max:100',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'judul_buku'      => 'required|array|min:1', // Berubah menjadi array karena bisa pinjam lebih dari 1
            'judul_buku.*'    => 'required|string|max:255', // Validasi isi array
            'tanggal_pinjam'  => 'required|date',
            'status'          => 'required|in:belum dikembalikan,sudah dikembalikan',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan atau ambil data Anggota
            $anggota = Anggota::firstOrCreate([
                'nama'          => $request->nama,
                'kelas'         => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            $berhasil = 0;
            $gagal = [];

            // 2. Looping (Perulangan) untuk setiap judul buku yang di-input
            foreach ($request->judul_buku as $judul) {
                // Lewati jika judul kosong
                if (trim($judul) == '') continue;

                $buku = Book::where('judul', 'LIKE', '%' . trim($judul) . '%')->first();
                
                // Jika buku tidak ada di database
                if (!$buku) {
                    $gagal[] = $judul . " (Tidak ditemukan)";
                    continue; // Lanjut ke buku berikutnya
                }
                
                // Jika stok habis tapi statusnya ingin meminjam
                if ($buku->stok <= 0 && $request->status == 'belum dikembalikan') {
                    $gagal[] = $buku->judul . " (Stok habis)";
                    continue; // Lanjut ke buku berikutnya
                }

                // 3. Simpan data pinjaman (masing-masing buku akan jadi 1 baris di tabel pinjaman)
                $pinjaman = Pinjaman::create([
                    'anggota_id'      => $anggota->id,
                    'buku_id'         => $buku->id,
                    'nama'            => $anggota->nama,
                    'kelas'           => $anggota->kelas,
                    'jenis_kelamin'   => $anggota->jenis_kelamin,
                    'judul_buku'      => $buku->judul,
                    'tanggal_pinjam'  => $request->tanggal_pinjam,
                    'tanggal_kembali' => $request->status == 'sudah dikembalikan' ? $request->tanggal_kembali : null,
                    'status'          => $request->status,
                    'denda'           => 0,
                ]);

                // 4. Update stok buku atau proses pengembalian langsung
                if ($pinjaman->status === 'sudah dikembalikan') {
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                } else {
                    $buku->decrement('stok');
                }

                $berhasil++;
            }

            DB::commit();

            // 5. Feedback Berdasarkan Hasil Looping
            if (count($gagal) > 0 && $berhasil > 0) {
                $pesanGagal = implode(', ', $gagal);
                return redirect()->route('pinjamans.index')->with('warning', "$berhasil buku berhasil dipinjam. Namun buku berikut gagal diproses: $pesanGagal");
            } elseif (count($gagal) > 0 && $berhasil == 0) {
                $pesanGagal = implode(', ', $gagal);
                return redirect()->back()->with('error', "Semua buku gagal dipinjam! Alasan: $pesanGagal")->withInput();
            }

            return redirect()->route('pinjamans.index')->with('success', 'Semua data pinjaman berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with(['pengembalian', 'anggota', 'buku'])->findOrFail($id);
        return view('pinjamans.show', compact('pinjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pinjaman = Pinjaman::with('anggota')->findOrFail($id);
        $anggota = Anggota::orderBy('nama')->get();
        $books = Book::orderBy('judul')->get();
        
        return view('pinjamans.edit', compact('pinjaman', 'anggota', 'books'));
    }

    /**
     * Update the specified resource in storage.
     * (Tetap menggunakan string biasa, karena proses edit dilakukan satu per satu per peminjaman buku)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kelas'           => 'required|string|max:100',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'judul_buku'      => 'required|string|max:255',
            'tanggal_pinjam'  => 'required|date',
            'status'          => 'required|in:belum dikembalikan,sudah dikembalikan',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        DB::beginTransaction();

        try {
            $pinjaman = Pinjaman::findOrFail($id);
            $statusLama = $pinjaman->status;
            
            $buku = Book::where('judul', 'LIKE', '%' . $request->judul_buku . '%')->first();
            
            if (!$buku && $request->status == 'belum dikembalikan') {
                return redirect()->back()->with('error', 'Buku tidak ditemukan!')->withInput();
            }
            
            $anggota = Anggota::firstOrCreate([
                'nama'          => $request->nama,
                'kelas'         => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            $pinjaman->update([
                'anggota_id'      => $anggota->id,
                'buku_id'         => $buku ? $buku->id : $pinjaman->buku_id,
                'nama'            => $anggota->nama,
                'kelas'           => $anggota->kelas,
                'jenis_kelamin'   => $anggota->jenis_kelamin,
                'judul_buku'      => $request->judul_buku,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->status == 'sudah dikembalikan' ? $request->tanggal_kembali : null,
                'status'          => $request->status,
            ]);

            if ($buku) {
                if ($statusLama == 'belum dikembalikan' && $request->status == 'sudah dikembalikan') {
                    $buku->increment('stok');
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                } else if ($statusLama == 'sudah dikembalikan' && $request->status == 'belum dikembalikan') {
                    if ($buku->stok > 0) {
                        $buku->decrement('stok');
                    }
                    Pengembalian::where('pinjaman_id', $pinjaman->id)->delete();
                    $pinjaman->update(['denda' => 0]);
                } else if ($request->status == 'sudah dikembalikan') {
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                }
            }

            DB::commit();
            return redirect()->route('pinjamans.index')->with('success', 'Data pinjaman berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pinjaman = Pinjaman::findOrFail($id);
            
            if ($pinjaman->status == 'belum dikembalikan' && $pinjaman->buku) {
                $pinjaman->buku->increment('stok');
            }
            
            Pengembalian::where('pinjaman_id', $id)->delete();
            $pinjaman->delete();

            DB::commit();
            return redirect()->route('pinjamans.index')->with('success', 'Data pinjaman berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process book return and calculate fine.
     */
    private function processPengembalian(Pinjaman $pinjaman, $tanggalKembali = null)
    {
        $tanggalPengembalian = $tanggalKembali ? Carbon::parse($tanggalKembali) : Carbon::now();
        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam);
        $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays(7);
        
        $keterlambatan = 0;
        if ($tanggalPengembalian->gt($tanggalJatuhTempo)) {
            $keterlambatan = $tanggalJatuhTempo->diffInDays($tanggalPengembalian);
        }
        
        $denda = $keterlambatan * 500;
        
        $pinjaman->update([
            'denda' => $denda,
            'tanggal_kembali' => $tanggalPengembalian->toDateString()
        ]);
        
        Pengembalian::updateOrCreate(
            ['pinjaman_id' => $pinjaman->id],
            [
                'nama'                 => $pinjaman->nama,
                'kelas'                => $pinjaman->kelas,
                'judul_buku'           => $pinjaman->judul_buku,
                'tanggal_kembali'      => $tanggalJatuhTempo->toDateString(),
                'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                'keterlambatan'        => $keterlambatan,
                'denda'                => $denda,
            ]
        );
    }

    /**
     * Mark as returned manually
     */
    public function markAsReturned($id)
    {
        DB::beginTransaction();

        try {
            $pinjaman = Pinjaman::findOrFail($id);
            
            if ($pinjaman->status == 'sudah dikembalikan') {
                return redirect()->back()->with('warning', 'Buku sudah dikembalikan sebelumnya!');
            }
            
            $this->processPengembalian($pinjaman);
            
            if ($pinjaman->buku) {
                $pinjaman->buku->increment('stok');
            }
            
            $pinjaman->update(['status' => 'sudah dikembalikan']);
            
            DB::commit();
            return redirect()->route('pinjamans.index')->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($pinjaman->denda, 0, ',', '.'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API: Get book stock info
     */
    public function getBookStock(Request $request)
    {
        $judul = $request->get('judul');
        $buku = Book::where('judul', 'LIKE', '%' . $judul . '%')->first();
        
        if ($buku) {
            return response()->json([
                'exists'   => true,
                'stok'     => $buku->stok,
                'tersedia' => $buku->stok > 0,
                'id'       => $buku->id
            ]);
        }
        
        return response()->json([
            'exists'   => false,
            'stok'     => 0,
            'tersedia' => false
        ]);
    }
}