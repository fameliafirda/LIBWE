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

    /**
     * FUNGSI PUSAT HITUNG DENDA
     * Dijamin 100% sama dengan logika di PinjamanController (Forward Counting)
     * Anti potong ganda untuk hari Minggu yang bertepatan dengan tanggal merah
     */
    private function hitungDendaBersih($tanggalPinjam, $tanggalPengembalian)
    {
        $tglPinjam = Carbon::parse($tanggalPinjam)->startOfDay();
        $tglJatuhTempo = $tglPinjam->copy()->addDays(7)->startOfDay();
        $tglKembali = Carbon::parse($tanggalPengembalian)->startOfDay();

        $keterlambatan = 0;

        // Hitung Keterlambatan jika melewati jatuh tempo
        if ($tglKembali->gt($tglJatuhTempo)) {
            $currentDate = $tglJatuhTempo->copy()->addDay();
            
            // Ambil data libur dari DB Master
            $daftarTanggalMerah = HariLibur::whereBetween('tanggal', [
                $tglJatuhTempo->toDateString(), 
                $tglKembali->toDateString()
            ])->pluck('tanggal')->toArray();
            
            while ($currentDate->lte($tglKembali)) {
                $isMinggu = $currentDate->isSunday();
                $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                // LOGIKA ANTI BOCOR: Jika hari ini Minggu ATAU Tanggal Merah, abaikan denda
                if ($isMinggu || $isTanggalMerah) {
                    // Jangan tambah keterlambatan
                } else {
                    // Hanya bertambah jika benar-benar hari kerja aktif
                    $keterlambatan++;
                }
                
                $currentDate->addDay();
            }
        }

        return [
            'keterlambatan' => $keterlambatan,
            'denda' => $keterlambatan * 500,
            'tanggal_kembali_seharusnya' => $tglJatuhTempo->toDateString()
        ];
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

            // Panggil Fungsi Pusat Hitung Denda
            $hasilHitung = $this->hitungDendaBersih($pinjaman->tanggal_pinjam, $validated['tanggal_pengembalian']);
            $tanggalPengembalianStr = Carbon::parse($validated['tanggal_pengembalian'])->toDateString();

            // Catat Pengembalian
            Pengembalian::create([
                'pinjaman_id' => $pinjaman->id,
                'nisn' => optional($pinjaman->anggota)->nisn ?? '-',
                'nama' => $pinjaman->nama,
                'kelas' => $pinjaman->kelas,
                'judul_buku' => $pinjaman->judul_buku,
                'tanggal_kembali' => $hasilHitung['tanggal_kembali_seharusnya'], 
                'tanggal_pengembalian' => $tanggalPengembalianStr,
                'keterlambatan' => $hasilHitung['keterlambatan'],
                'denda' => $hasilHitung['denda']
            ]);

            // Update status Pinjaman
            $pinjaman->update([
                'status' => 'sudah dikembalikan', 
                'denda' => $hasilHitung['denda'], 
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
            
            // Panggil Fungsi Pusat Hitung Denda untuk Update
            $hasilHitung = $this->hitungDendaBersih($pinjaman->tanggal_pinjam, $validated['tanggal_pengembalian']);
            $tanggalPengembalianStr = Carbon::parse($validated['tanggal_pengembalian'])->toDateString();

            // Perbarui Data Pengembalian
            $pengembalian->update([
                'tanggal_pengembalian' => $tanggalPengembalianStr,
                'keterlambatan' => $hasilHitung['keterlambatan'],
                'denda' => $hasilHitung['denda']
            ]);

            if ($pinjaman) {
                $pinjaman->update([
                    'denda' => $hasilHitung['denda'], 
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