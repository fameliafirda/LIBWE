<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Pengembalian;
use App\Models\Anggota;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
     * API untuk mengambil data anggota berdasarkan NISN secara otomatis (AJAX)
     */
    public function getAnggotaByNisn($nisn)
    {
        $anggota = Anggota::where('nisn', $nisn)->first();

        if ($anggota) {
            return response()->json([
                'success' => true,
                'data' => $anggota
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Siswa dengan NISN tersebut tidak terdaftar sebagai anggota!'
        ]);
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
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn'            => 'required|string', 
            'judul_buku'      => 'required|array|min:1', 
            'judul_buku.*'    => 'required|string|max:255', 
            'tanggal_pinjam'  => 'required|date',
            'status'          => 'required|in:belum dikembalikan,sudah dikembalikan',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        DB::beginTransaction();

        try {
            $anggota = Anggota::where('nisn', $request->nisn)->first();

            if (!$anggota) {
                return redirect()->back()->with('error', 'Siswa dengan NISN tersebut tidak terdaftar di data Anggota!')->withInput();
            }

            $berhasil = 0;
            $gagal = [];

            foreach ($request->judul_buku as $judul) {
                if (trim($judul) == '') continue;

                $buku = Book::where('judul', 'LIKE', '%' . trim($judul) . '%')->first();
                
                if (!$buku) {
                    $gagal[] = $judul . " (Tidak ditemukan)";
                    continue; 
                }
                
                if ($buku->stok <= 0 && $request->status == 'belum dikembalikan') {
                    $gagal[] = $buku->judul . " (Stok habis)";
                    continue; 
                }

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

                if ($pinjaman->status === 'sudah dikembalikan') {
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                } else {
                    $buku->decrement('stok');
                }

                $berhasil++;
            }

            DB::commit();

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
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn'            => 'required|string',
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
            
            $anggota = Anggota::where('nisn', $request->nisn)->first();

            if (!$anggota) {
                return redirect()->back()->with('error', 'Siswa dengan NISN tersebut tidak terdaftar di data Anggota!')->withInput();
            }

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
     * Sabtu TETAP MASUK (Denda), Minggu LIBUR, Tanggal Merah & Cuti Bersama LIBUR.
     */
    private function processPengembalian(Pinjaman $pinjaman, $tanggalKembali = null)
    {
        $tanggalPengembalian = $tanggalKembali 
            ? Carbon::parse($tanggalKembali)->timezone('Asia/Jakarta')->startOfDay() 
            : Carbon::now('Asia/Jakarta')->startOfDay();
            
        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
        $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays(7); 
        
        $keterlambatan = 0;
        
        if ($tanggalPengembalian->gt($tanggalJatuhTempo)) {
            $tahun = $tanggalJatuhTempo->format('Y');
            $daftarTanggalMerah = $this->getDaftarLiburNasional($tahun);

            $currentDate = $tanggalJatuhTempo->copy()->addDay();
            
            while ($currentDate->lte($tanggalPengembalian)) {
                $isMinggu = $currentDate->isSunday(); 
                $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                // Denda hanya bertambah jika bukan hari Minggu dan bukan tanggal merah nasional/cuti bersama
                if (!$isMinggu && !$isTanggalMerah) {
                    $keterlambatan++;
                }

                $currentDate->addDay();
            }
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
     * Helper Master Kalender Libur Nasional & Cuti Bersama Indonesia Resmi 2026
     */
    private function getDaftarLiburNasional($tahun)
    {
        return Cache::remember("libur_indonesia_murni_{$tahun}", 86400, function () use ($tahun) {
            $tanggalMerah = [];

            if ($tahun == 2026) {
                $tanggalMerah = [
                    '2026-01-01', // Tahun Baru 2026 Masehi
                    '2026-01-23', // Cuti Bersama Imlek
                    '2026-01-24', // Tahun Baru Imlek 2577
                    '2026-02-15', // Isra Mikraj Nabi Muhammad SAW
                    '2026-03-19', // Hari Suci Nyepi (Tahun Baru Saka 1948)
                    '2026-03-20', // Cuti Bersama Nyepi
                    '2026-03-21', // Cuti Bersama Nyepi
                    '2026-04-03', // Wafat Isa Almasih (Jumat Agung)
                    '2026-04-05', // Hari Paskah
                    '2026-05-01', // Hari Buruh Internasional
                    '2026-05-14', // Kenaikan Isa Almasih
                    '2026-05-15', // Cuti Bersama Kenaikan Isa Almasih (Jumat)
                    '2026-05-24', // Hari Raya Waisak 2570
                    '2026-05-25', // Cuti Bersama Waisak (Senin)
                    '2026-06-01', // Hari Lahir Pancasila
                    '2026-11-27', // Idul Adha 1447 H
                    '2026-12-25', // Hari Raya Natal
                ];
            }

            try {
                $url = "https://dayoffapi.vercel.app/api?year=" . $tahun;
                $response = @file_get_contents($url);
                if ($response) {
                    $data = json_decode($response, true);
                    if (is_array($data)) {
                        foreach ($data as $row) {
                            if (isset($row['tanggal'])) {
                                $tanggalMerah[] = $row['tanggal'];
                            }
                        }
                    }
                }
            } catch (\Exception $e) { }

            return array_unique($tanggalMerah);
        });
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