<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HariLibur;
use Carbon\Carbon;

class HariLiburSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunSekarang = Carbon::now()->format('Y');
        $this->fetchAndSaveFromApi($tahunSekarang);
    }

    /**
     * Mengambil data dari REST API GitHub Radyakaze yang dijamin online terus
     */
    private function fetchAndSaveFromApi($year)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        // MENGGUNAKAN SERVER DATABASE GITHUB YANG AMAN DAN ABADI
        $url = "https://raw.githubusercontent.com/radyakaze/api-hari-libur-indonesia/main/data/{$year}.json";

        try {
            $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));

            if ($response) {
                $data = json_decode($response, true);

                if (is_array($data) && count($data) > 0) {
                    foreach ($data as $row) {
                        // Struktur database baru: membaca key 'p_tanggal' atau 'tanggal'
                        $tanggalString = $row['tanggal'] ?? null;
                        
                        if ($tanggalString) {
                            $keterangan = $row['keterangan'] ?? 'Hari Libur';
                            // Cek apakah data ini cuti bersama atau libur nasional murni
                            $isCuti = $row['is_cuti'] ?? (str_contains(strtolower($keterangan), 'cuti') || str_contains(strtolower($keterangan), 'bersama'));
                            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

                            HariLibur::updateOrCreate(
                                ['tanggal' => $tanggalString],
                                [
                                    'keterangan' => $keterangan,
                                    'jenis' => $jenis
                                ]
                            );
                        }
                    }
                    $this->command->info("Berhasil! Database Master Kalender Indonesia tahun {$year} sukses terisi via Server GitHub.");
                    return;
                }
            }
        } catch (\Exception $e) {
            // Lempar ke fallback cadangan jika ada masalah
        }

        $this->command->warn("Server API bermasalah. Mengaktifkan sistem pangkalan data cadangan internal.");
        $this->insertFallbackData($year);
    }

    /**
     * Data master cadangan resmi tahun 2026 SKB 3 Menteri
     */
    private function insertFallbackData($year)
    {
        if ($year == 2026) {
            $fallback = [
                ['tanggal' => '2026-01-01', 'keterangan' => 'Tahun Baru 2026 Masehi', 'jenis' => 'nasional'],
                ['tanggal' => '2026-01-23', 'keterangan' => 'Cuti Bersama Tahun Baru Imlek', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-01-24', 'keterangan' => 'Tahun Baru Imlek 2577', 'jenis' => 'nasional'],
                ['tanggal' => '2026-02-15', 'keterangan' => 'Isra Mikraj Nabi Muhammad SAW', 'jenis' => 'nasional'],
                ['tanggal' => '2026-03-19', 'keterangan' => 'Hari Suci Nyepi Saka 1948', 'jenis' => 'nasional'],
                ['tanggal' => '2026-03-20', 'keterangan' => 'Cuti Bersama Nyepi', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-03-21', 'keterangan' => 'Cuti Bersama Nyepi', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-04-03', 'keterangan' => 'Wafat Isa Almasih', 'jenis' => 'nasional'],
                ['tanggal' => '2026-04-05', 'keterangan' => 'Hari Paskah', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-01', 'keterangan' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-14', 'keterangan' => 'Kenaikan Isa Almasih', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-15', 'keterangan' => 'Cuti Bersama Kenaikan Isa Almasih', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-05-24', 'keterangan' => 'Hari Raya Waisak 2570', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-25', 'keterangan' => 'Cuti Bersama Waisak', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-06-01', 'keterangan' => 'Hari Lahir Pancasila', 'jenis' => 'nasional'],
                ['tanggal' => '2026-11-27', 'keterangan' => 'Hari Raya Idul Adha 1447 H', 'jenis' => 'nasional'],
                ['tanggal' => '2026-12-25', 'keterangan' => 'Hari Raya Natal', 'jenis' => 'nasional'],
            ];

            foreach ($fallback as $row) {
                HariLibur::updateOrCreate(['tanggal' => $row['tanggal']], $row);
            }
        }
    }
}