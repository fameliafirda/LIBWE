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
     * FITUR REVISI UTAMA: Jalankan Sinkronisasi Tahunan Menggunakan Server Repositori GitHub Baru
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

        // SINKRONISASI MENGGUNAKAN JALUR REPO GITHUB PER TAHUN YANG DIJAMIN SELALU AKTIF
        $url = "https://raw.githubusercontent.com/radyakaze/api-hari-libur-indonesia/main/data/{$year}.json";

        try {
            $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));

            if ($response) {
                $data = json_decode($response, true);

                if (is_array($data) && count($data) > 0) {
                    $inserted = 0;
                    foreach ($data as $row) {
                        $tanggalString = $row['tanggal'] ?? null;

                        if ($tanggalString) {
                            $keterangan = $row['keterangan'] ?? 'Hari Libur';
                            $isCuti = $row['is_cuti'] ?? (str_contains(strtolower($keterangan), 'cuti') || str_contains(strtolower($keterangan), 'bersama'));
                            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

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
                        ->with('success', "Berhasil menarik data resmi SKB 3 Menteri! {$inserted} data hari libur tahun {$year} berhasil dimasukkan ke DB Master.");
                }
            }
        } catch (\Exception $e) {
            // Gagal jika offline
        }

        return redirect()->back()->with('error', "Gagal menghubungi repositori data kalender. Pastikan koneksi internet wifi terhubung!");
    }
}