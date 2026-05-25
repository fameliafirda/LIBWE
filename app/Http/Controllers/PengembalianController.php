<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjaman;
use App\Models\Book;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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

        $pinjaman = Pinjaman::with('anggota')->findOrFail($validated['pinjaman_id']);

        if (Pengembalian::where('pinjaman_id', $pinjaman->id)->exists()) {
            return redirect()->back()->with('error', 'Pengembalian untuk peminjaman ini sudah dicatat.');
        }

        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->startOfDay();
        $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7)->startOfDay();
        $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->startOfDay();

        $lamaTerlambat = 0;
        if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
            $tahun = $tanggalHarusKembali->format('Y');
            $daftarTanggalMerah = $this->getDaftarLiburNasional($tahun);

            $currentDate = $tanggalHarusKembali->copy()->addDay();
            
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

        $pinjaman->update([
            'status' => 'sudah dikembalikan', 
            'denda' => $denda, 
            'tanggal_kembali' => $tanggalPengembalian->toDateString()
        ]);
        
        $buku = Book::where('judul', 'LIKE', '%' . trim($pinjaman->judul_buku) . '%')->first();
        if ($buku) {
            $buku->increment('stok');
        }

        return redirect()->route('pengembalians.index')->with('success', 'Pengembalian berhasil dicatat.');
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

        $pinjaman = $pengembalian->pinjaman;
        
        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->startOfDay();
        $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7)->startOfDay();
        $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->startOfDay();

        $lamaTerlambat = 0;
        if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
            $tahun = $tanggalHarusKembali->format('Y');
            $daftarTanggalMerah = $this->getDaftarLiburNasional($tahun);

            $currentDate = $tanggalHarusKembali->copy()->addDay();
            
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

        return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(Pengembalian $pengembalian)
    {
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
        return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }

    private function getDaftarLiburNasional($tahun)
    {
        return Cache::remember("libur_indonesia_murni_{$tahun}", 86400, function () use ($tahun) {
            $tanggalMerah = [];

            if ($tahun == 2026) {
                $tanggalMerah = [
                    '2026-01-01', '2026-01-23', '2026-01-24', '2026-02-15', 
                    '2026-03-19', '2026-03-20', '2026-03-21', '2026-04-03', 
                    '2026-04-05', '2026-05-01', '2026-05-14', '2026-05-15', 
                    '2026-05-24', '2026-05-25', '2026-06-01', '2026-11-27', 
                    '2026-12-25',
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
}