<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Pengembalian;
use App\Models\Anggota;
use App\Models\Book;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
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

    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        $books = Book::where('stok', '>', 0)->orderBy('judul')->get();
        $bookTitles = Book::pluck('judul')->toArray();
        
        return view('pinjamans.create', compact('anggota', 'books', 'bookTitles'));
    }

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
                'message' => 'Buku "' . $judul . '" tidak ditemukan di database perpustakaan.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

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
                return redirect()->back()->with('error', 'Siswa dengan NISN tersebut tidak terdaftar!')->withInput();
            }

            $berhasil = 0;
            foreach ($request->judul_buku as $judul) {
                if (trim($judul) == '') continue;

                $buku = Book::where('judul', 'LIKE', '%' . trim($judul) . '%')->first();
                
                if (!$buku) continue; 
                
                if ($buku->stok <= 0 && $request->status == 'belum dikembalikan') continue;

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
            return redirect()->route('pinjamans.index')->with('success', 'Data pinjaman berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

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
            $anggota = Anggota::where('nisn', $request->nisn)->first();

            if (!$anggota) {
                return redirect()->back()->with('error', 'Anggota tidak ditemukan!')->withInput();
            }

            $pinjaman->update([
                'anggota_id'      => $anggota->id,
                'buku_id'         => $buku ? $buku->id : $pinjaman->buku_id,
                'nama'            => $anggota->nama,
                'kelas'           => $anggota->kelas,
                'jenis_kelamin'   => $anggota->jenis_kelamin,
                'judul_buku'      => $buku ? $buku->judul : $request->judul_buku,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->status == 'sudah dikembalikan' ? $request->tanggal_kembali : null,
                'status'          => $request->status,
            ]);

            if ($buku) {
                if ($statusLama == 'belum dikembalikan' && $request->status == 'sudah dikembalikan') {
                    $buku->increment('stok');
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                } else if ($statusLama == 'sudah dikembalikan' && $request->status == 'belum dikembalikan') {
                    $buku->decrement('stok');
                    Pengembalian::where('pinjaman_id', $pinjaman->id)->delete();
                    $pinjaman->update(['denda' => 0]);
                } else if ($request->status == 'sudah dikembalikan') {
                    $this->processPengembalian($pinjaman, $request->tanggal_kembali);
                }
            }

            DB::commit();
            return redirect()->route('pinjamans.index')->with('success', 'Data berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function markAsReturned($id)
    {
        DB::beginTransaction();
        try {
            $pinjaman = Pinjaman::findOrFail($id);
            if ($pinjaman->status == 'sudah dikembalikan') {
                return redirect()->back()->with('warning', 'Buku sudah dikembalikan!');
            }
            
            // FUNGSI INI YANG DIKLIK LEWAT TOMBOL CENTANG HIJAU
            $this->processPengembalian($pinjaman);
            
            $buku = Book::where('judul', 'LIKE', '%' . trim($pinjaman->judul_buku) . '%')->first();
            if ($buku) {
                $buku->increment('stok');
            }
            
            DB::commit();
            return redirect()->route('pinjamans.index')->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * CORE LOGIC: PROSES PENGEMBALIAN & HITUNG DENDA
     * KINI 1000% SINKRON DENGAN ARRAY MANUAL VIEW BLADE PEMINJAMAN
     */
    private function processPengembalian(Pinjaman $pinjaman, $tanggalKembaliInput = null)
    {
        $tglPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
        $tglJatuhTempo = $tglPinjam->copy()->addDays(7)->startOfDay();
        
        $tglKembaliAktual = $tanggalKembaliInput ? Carbon::parse($tanggalKembaliInput)->timezone('Asia/Jakarta')->startOfDay() : Carbon::now('Asia/Jakarta')->startOfDay();
        $tglKembaliStr = $tglKembaliAktual->toDateString();
        
        $keterlambatan = 0;

        // INILAH SUMBER MASALAHNYA! SEKARANG KITA GANTI PAKAI ARRAY MANUAL DARI VIEW KAMU
        if ($tglKembaliAktual->gt($tglJatuhTempo)) {
           // Ambil data tanggal libur langsung dari database
$daftarTanggalMerah = \App\Models\HariLibur::pluck('tanggal')
    ->map(function($t) {
        return \Carbon\Carbon::parse($t)->toDateString();
    })->toArray();

            $currentDate = $tglJatuhTempo->copy()->addDay();
            
            // Perulangan yang sama persis
            while ($currentDate->lte($tglKembaliAktual)) {
                $isMinggu = $currentDate->isSunday(); 
                $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                if (!$isMinggu && !$isTanggalMerah) {
                    $keterlambatan++;
                }
                $currentDate->addDay();
            }
        }
        
        $denda = $keterlambatan * 500;
        
        $pinjaman->update([
            'status' => 'sudah dikembalikan',
            'denda' => $denda,
            'tanggal_kembali' => $tglKembaliStr
        ]);
        
        Pengembalian::updateOrCreate(
            ['pinjaman_id' => $pinjaman->id],
            [
                'nisn'                 => optional($pinjaman->anggota)->nisn ?? '-',
                'nama'                 => $pinjaman->nama,
                'kelas'                => $pinjaman->kelas,
                'judul_buku'           => $pinjaman->judul_buku,
                'tanggal_kembali'      => $tglJatuhTempo->toDateString(), 
                'tanggal_pengembalian' => $tglKembaliStr,                
                'keterlambatan'        => $keterlambatan,
                'denda'                => $denda,
            ]
        );
    }

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
            return redirect()->route('pinjamans.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pinjaman = Pinjaman::with(['pengembalian', 'anggota', 'buku'])->findOrFail($id);
        return view('pinjamans.show', compact('pinjaman'));
    }

    public function edit($id)
    {
        $pinjaman = Pinjaman::with('anggota')->findOrFail($id);
        $anggota = Anggota::orderBy('nama')->get();
        $books = Book::orderBy('judul')->get();
        return view('pinjamans.edit', compact('pinjaman', 'anggota', 'books'));
    }

    public function getBookStock(Request $request)
    {
        $judul = $request->get('judul');
        $buku = Book::where('judul', 'LIKE', '%' . $judul . '%')->first();
        return response()->json([
            'exists'   => (bool)$buku,
            'stok'     => $buku->stok ?? 0,
            'tersedia' => ($buku && $buku->stok > 0),
            'id'       => $buku->id ?? null
        ]);
    }
}