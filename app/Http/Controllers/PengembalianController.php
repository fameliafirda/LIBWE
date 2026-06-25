<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjaman;
use App\Models\Book;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with('pinjaman.anggota')->latest()->get();
        $pinjamans = Pinjaman::paginate(10);
        $kelasList = Anggota::distinct()->pluck('kelas')->sort();

        return view('pengembalians.index', compact('pengembalians', 'pinjamans', 'kelasList'));
    }

    public function create()
    {
        $pinjamans = Pinjaman::with('anggota')->where('status', 'belum dikembalikan')->get();
        return view('pengembalians.create', compact('pinjamans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_pengembalian' => 'required|date'
        ]);

        DB::beginTransaction();

        try {
            $pinjaman = Pinjaman::with('anggota')->findOrFail($validated['pinjaman_id']);

            if (Pengembalian::where('pinjaman_id', $pinjaman->id)->exists()) {
                return redirect()->back()->with('error', 'Pengembalian untuk peminjaman ini sudah dicatat.');
            }

            // COCOKKAN TOTAL LOGIKA DENGAN PINJAMAN BLADE (MENGGUNAKAN ASIA/JAKARTA)
            $tglPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
            $tanggalHarusKembali = $tglPinjam->copy()->addDays(7)->startOfDay();
            
            // Tanggal pengembalian di-set ke Asia/Jakarta agar startOfDay nya presisi tidak minus jam server
            $tanggalPengembalianAktual = Carbon::parse($validated['tanggal_pengembalian'])->timezone('Asia/Jakarta')->startOfDay();

            $lamaTerlambat = 0;

            if ($tanggalPengembalianAktual->gt($tanggalHarusKembali)) {
                // DAFTAR ARRAY MANUAL YANG SAMA PERSIS DENGAN PINJAMAN BLADE KAMU
                $daftarTanggalMerah = [
                    '2026-01-01', '2026-01-23', '2026-01-24', '2026-02-15', 
                    '2026-03-19', '2026-03-20', '2026-03-21', '2026-04-03', 
                    '2026-04-05', '2026-05-01', '2026-05-14', '2026-05-15', 
                    '2026-05-24', '2026-05-25', '2026-06-01', '2026-11-27', 
                    '2026-12-25',
                ];

                $currentDate = $tanggalHarusKembali->copy()->addDay();
                
                // Menggunakan lte() dan startOfDay() yang sudah terkunci zona waktunya agar rentang harinya pas
                while ($currentDate->lte($tanggalPengembalianAktual)) {
                    $isMinggu = $currentDate->isSunday(); 
                    $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                    if (!$isMinggu && !$isTanggalMerah) {
                        $lamaTerlambat++;
                    }
                    $currentDate->addDay();
                }
            }

            $denda = $lamaTerlambat * 500;

            // Catat ke tabel Pengembalian
            Pengembalian::create([
                'pinjaman_id' => $pinjaman->id,
                'nisn' => optional($pinjaman->anggota)->nisn ?? '-',
                'nama' => $pinjaman->nama,
                'kelas' => $pinjaman->kelas,
                'judul_buku' => $pinjaman->judul_buku,
                'tanggal_kembali' => $tanggalHarusKembali->toDateString(), 
                'tanggal_pengembalian' => $tanggalPengembalianAktual->toDateString(),
                'keterlambatan' => $lamaTerlambat, 
                'denda' => $denda
            ]);

            // Update status Pinjaman
            $pinjaman->update([
                'status' => 'sudah dikembalikan', 
                'denda' => $denda, 
                'tanggal_kembali' => $tanggalPengembalianAktual->toDateString()
            ]);
            
            // Kembalikan Stok Buku
            $buku = Book::where('judul', 'LIKE', '%' . trim($pinjaman->judul_buku) . '%')->first();
            if ($buku) {
                $buku->increment('stok');
            }

            DB::commit();
            return redirect()->route('pengembalians.index')->with('success', 'Pengembalian berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Pengembalian $pengembalian)
    {
        return view('pengembalians.edit', compact('pengembalian'));
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $validated = $request->validate([
            'tanggal_pengembalian' => 'required|date'
        ]);

        DB::beginTransaction();

        try {
            $pinjaman = $pengembalian->pinjaman;
            
            $tglPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
            $tanggalHarusKembali = $tglPinjam->copy()->addDays(7)->startOfDay();
            $tanggalPengembalianAktual = Carbon::parse($validated['tanggal_pengembalian'])->timezone('Asia/Jakarta')->startOfDay();

            $lamaTerlambat = 0;

            if ($tanggalPengembalianAktual->gt($tanggalHarusKembali)) {
                $daftarTanggalMerah = [
                    '2026-01-01', '2026-01-23', '2026-01-24', '2026-02-15', 
                    '2026-03-19', '2026-03-20', '2026-03-21', '2026-04-03', 
                    '2026-04-05', '2026-05-01', '2026-05-14', '2026-05-15', 
                    '2026-05-24', '2026-05-25', '2026-06-01', '2026-11-27', 
                    '2026-12-25',
                ];

                $currentDate = $tanggalHarusKembali->copy()->addDay();
                
                while ($currentDate->lte($tanggalPengembalianAktual)) {
                    $isMinggu = $currentDate->isSunday(); 
                    $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                    if (!$isMinggu && !$isTanggalMerah) {
                        $lamaTerlambat++;
                    }
                    $currentDate->addDay();
                }
            }

            $denda = $lamaTerlambat * 500;

            // Perbarui Data Pengembalian
            $pengembalian->update([
                'tanggal_pengembalian' => $tanggalPengembalianAktual->toDateString(),
                'keterlambatan' => $lamaTerlambat,
                'denda' => $denda
            ]);

            if ($pinjaman) {
                $pinjaman->update([
                    'denda' => $denda, 
                    'tanggal_kembali' => $tanggalPengembalianAktual->toDateString()
                ]);
            }

            DB::commit();
            return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Pengembalian $pengembalian)
    {
        DB::beginTransaction();

        try {
            $pinjaman = $pengembalian->pinjaman;
            
            if ($pinjaman) {
                $pinjaman->update([
                    'status' => 'belum dikembalikan', 
                    'denda' => 0, 
                    'tanggal_kembali' => null
                ]);
                
                $buku = Book::where('judul', 'LIKE', '%' . trim($pinjaman->judul_buku) . '%')->first();
                if ($buku && $buku->stok > 0) {
                    $buku->decrement('stok'); 
                }
            }
            
            $pengembalian->delete();

            DB::commit();
            return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}