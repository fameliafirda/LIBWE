<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjaman;
use App\Models\Book;
use App\Models\Anggota;
use App\Models\HariLibur;
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

            // Hitung Keterlambatan & Denda (Menggunakan DB Master HariLibur & Cek Hari Minggu)
            $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->startOfDay();
            $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7)->startOfDay();
            $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->startOfDay();

            $lamaTerlambat = 0;
            if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
                $currentDate = $tanggalHarusKembali->copy()->addDay();
                
                $daftarTanggalMerah = HariLibur::whereBetween('tanggal', [
                    $tanggalHarusKembali->toDateString(), 
                    $tanggalPengembalian->toDateString()
                ])->pluck('tanggal')->toArray();
                
                while ($currentDate->lte($tanggalPengembalian)) {
                    $isMinggu = $currentDate->isSunday();
                    $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                    if (!$isMinggu && !$isTanggalMerah) {
                        $lamaTerlambat++;
                    }
                    $currentDate->addDay();
                }
            }

            $denda = $lamaTerlambat * 500;

            // Catat Pengembalian
            Pengembalian::create([
                'pinjaman_id' => $pinjaman->id,
                'nisn' => optional($pinjaman->anggota)->nisn ?? '-',
                'nama' => $pinjaman->nama,
                'kelas' => $pinjaman->kelas,
                'judul_buku' => $pinjaman->judul_buku,
                'tanggal_kembali' => $tanggalHarusKembali->toDateString(), 
                'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                'keterlambatan' => $lamaTerlambat,
                'denda' => $denda
            ]);

            // Update status Pinjaman
            $pinjaman->update([
                'status' => 'sudah dikembalikan', 
                'denda' => $denda, 
                'tanggal_kembali' => $tanggalPengembalian->toDateString()
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
            
            $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->startOfDay();
            $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7)->startOfDay();
            $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->startOfDay();

            $lamaTerlambat = 0;
            if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
                $currentDate = $tanggalHarusKembali->copy()->addDay();
                
                $daftarTanggalMerah = HariLibur::whereBetween('tanggal', [
                    $tanggalHarusKembali->toDateString(), 
                    $tanggalPengembalian->toDateString()
                ])->pluck('tanggal')->toArray();
                
                while ($currentDate->lte($tanggalPengembalian)) {
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
                'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                'keterlambatan' => $lamaTerlambat,
                'denda' => $denda
            ]);

            if ($pinjaman) {
                $pinjaman->update([
                    'denda' => $denda, 
                    'tanggal_kembali' => $tanggalPengembalian->toDateString()
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
                // Kembalikan status peminjaman menjadi aktif kembali
                $pinjaman->update([
                    'status' => 'belum dikembalikan', 
                    'denda' => 0, 
                    'tanggal_kembali' => null
                ]);
                
                // Potong stok kembali karena status buku dipinjam lagi
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