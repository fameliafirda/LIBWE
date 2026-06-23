<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HariLiburController extends Controller
{
    /**
     * Tampilkan Halaman Master Kalender Libur dengan Filter Tahun
     */
    public function index(Request $request)
    {
        $tahunDipilih = $request->get('tahun', Carbon::now()->format('Y'));

        $hariLiburs = HariLibur::whereYear('tanggal', $tahunDipilih)
            ->orderBy('tanggal', 'asc')
            ->get();

        $daftarTahun = HariLibur::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([Carbon::now()->format('Y')]);
        }

        return view('hari_liburs.index', compact('hariLiburs', 'daftarTahun', 'tahunDipilih'));
    }

    /**
     * Tambah Hari Libur Secara Manual lewat Form Modal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date|unique:hari_liburs,tanggal',
            'keterangan' => 'required|string|max:255',
            'jenis'      => 'required|in:nasional,cuti_bersama',
        ]);

        HariLibur::create($validated);

        return redirect()->back()->with('success', 'Hari libur baru berhasil ditambahkan ke DB Master!');
    }

    /**
     * Update Data Hari Libur
     */
    public function update(Request $request, $id)
    {
        $hariLibur = HariLibur::findOrFail($id);

        $validated = $request->validate([
            'tanggal'    => 'required|date|unique:hari_liburs,tanggal,' . $id,
            'keterangan' => 'required|string|max:255',
            'jenis'      => 'required|in:nasional,cuti_bersama',
        ]);

        $hariLibur->update($validated);

        return redirect()->back()->with('success', 'Data lencana kalender berhasil diperbarui!');
    }

    /**
     * Hapus Data Hari Libur dari Database Master
     */
    public function destroy($id)
    {
        $hariLibur = HariLibur::findOrFail($id);
        $hariLibur->delete();

        return redirect()->back()->with('success', 'Hari libur berhasil dihapus dari database.');
    }

    /**
     * FITUR REVISI: Jalankan Sinkronisasi Tahunan Menggunakan Jalur Repositori GitHub Andi Fahruddin
     */
    public function generateTahun(Request $request)
    {
        $request->validate([
            'tahun_generate' => 'required|numeric|min:2020|max:2050',
        ]);

        $year = $request->tahun_generate;

        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        // DISINKRONKAN KE REPO GITHUB BARU PILIHANMU
        $url = "https://raw.githubusercontent.com/andifahruddinakas/api-hari-libur/main/data/{$year}.json";

        try {
            $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));

            if ($response) {
                $data = json_decode($response, true);

                if (is_array($data) && count($data) > 0) {
                    $inserted = 0;
                    foreach ($data as $row) {
                        $tanggalString = $row['date'] ?? null;

                        if ($tanggalString) {
                            $keterangan = $row['title'] ?? 'Hari Libur';
                            $isCuti = $row['is_cuti'] ?? false;
                            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

                            // FILTER: Kunci hari Minggu agar tidak masuk database, karena Minggu diurus eksklusif oleh Carbon
                            $cekHari = Carbon::parse($tanggalString);
                            if ($cekHari->isSunday()) {
                                continue; 
                            }

                            HariLibur::updateOrCreate(
                                ['tanggal' => $tanggalString],
                                [
                                    'keterangan' => $keterangan,
                                    'jenis' => $jenis
                                ]
                            );
                            $inserted++;
                        }
                    }
                    return redirect()->route('hari-liburs.index', ['tahun' => $year])
                        ->with('success', "Berhasil menarik data resmi SKB 3 Menteri! {$inserted} data hari libur (Senin-Sabtu) tahun {$year} masuk ke DB Master.");
                }
            }
        } catch (\Exception $e) {
            // Gagal jika offline
        }

        return redirect()->back()->with('error', "Gagal menghubungi repositori data kalender GitHub. Pastikan koneksi internet wifi terhubung!");
    }
}