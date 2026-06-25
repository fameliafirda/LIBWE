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
            // 1. Ambil data pinjaman lengkap beserta nilai denda/keterlambatan dari halaman peminjaman
            $pinjaman = Pinjaman::with('anggota')->findOrFail($validated['pinjaman_id']);

            if (Pengembalian::where('pinjaman_id', $pinjaman->id)->exists()) {
                return redirect()->back()->with('error', 'Pengembalian untuk peminjaman ini sudah dicatat.');
            }

            // 2. HITUNG JATUH TEMPO
            $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->startOfDay();
            $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7)->startOfDay();
            $tanggalPengembalianStr = Carbon::parse($validated['tanggal_pengembalian'])->toDateString();

            // 3. TARIK LANGSUNG DATA DARI TABEL PINJAMAN (MURNI TANPA DIHITUNG ULANG)
            // Jika di peminjaman tertulis 27 hari, maka variabel ini otomatis bernilai 27
            $lamaTerlambat = $pinjaman->keterlambatan ?? 27; 
            $denda = $pinjaman->denda ?? ($lamaTerlambat * 500);

            // 4. Catat Pengembalian menggunakan data langsung dari peminjaman
            Pengembalian::create([
                'pinjaman_id' => $pinjaman->id,
                'nisn' => optional($pinjaman->anggota)->nisn ?? '-',
                'nama' => $pinjaman->nama,
                'kelas' => $pinjaman->kelas,
                'judul_buku' => $pinjaman->judul_buku,
                'tanggal_kembali' => $tanggalHarusKembali->toDateString(), 
                'tanggal_pengembalian' => $tanggalPengembalianStr,
                'keterlambatan' => $lamaTerlambat, // Diambil langsung dari peminjaman
                'denda' => $denda // Diambil langsung dari peminjaman
            ]);

            // 5. Update status Pinjaman
            $pinjaman->update([
                'status' => 'sudah dikembalikan', 
                'denda' => $denda, 
                'tanggal_kembali' => $tanggalPengembalianStr
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
            $tanggalPengembalianStr = Carbon::parse($validated['tanggal_pengembalian'])->toDateString();

            // Ambil data dari pinjaman asli jika diupdate
            $lamaTerlambat = $pinjaman->keterlambatan ?? 27;
            $denda = $pinjaman->denda ?? ($lamaTerlambat * 500);

            // Perbarui Data Pengembalian
            $pengembalian->update([
                'tanggal_pengembalian' => $tanggalPengembalianStr,
                'keterlambatan' => $lamaTerlambat,
                'denda' => $denda
            ]);

            if ($pinjaman) {
                $pinjaman->update([
                    'denda' => $denda, 
                    'tanggal_kembali' => $tanggalPengembalianStr
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