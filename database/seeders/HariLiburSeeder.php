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
     * Mengambil data langsung dari file JSON mentah di GitHub Andi Fahruddin
     */
    private function fetchAndSaveFromApi($year)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        // MENEMBAK LINK RAW JSON DARI REPOSITORI PILIHANMU
        $url = "https://raw.githubusercontent.com/andifahruddinakas/api-hari-libur/main/data/{$year}.json";

        try {
            $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));

            if ($response) {
                $data = json_decode($response, true);

                // PERBAIKAN: Cukup gunakan is_array untuk memastikan format JSON valid
                if (is_array($data) && count($data) > 0) {
                    foreach ($data as $row) {
                        $tanggalString = $row['date'] ?? null;
                        
                        if ($tanggalString) {
                            $keterangan = $row['title'] ?? 'Hari Libur';
                            
                            // Cek tipenya: apakah Cuti Bersama atau Libur Nasional murni
                            $isCuti = $row['is_cuti'] ?? false;
                            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

                            // FILTER: Hari Minggu dikeluarkan dari DB agar dihitung eksklusif oleh Carbon
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
                        }
                    }
                    $this->command->info("Berhasil! Database Master Kalender Indonesia tahun {$year} sukses terisi via GitHub andifahruddinakas.");
                    return;
                }
            }
        } catch (\Exception $e) {
            // Lari ke fallback cadangan jika offline
        }

        $this->command->warn("Koneksi gagal. Mengaktifkan data cadangan internal.");
        $this->insertFallbackData($year);
    }

    /**
     * Data cadangan darurat (Hanya Senin - Sabtu, Minggu dikeluarkan)
     */
    private function insertFallbackData($year)
    {
        if ($year == 2026) {
            $fallback = [
                ['tanggal' => '2026-01-01', 'keterangan' => 'Tahun Baru 2026 Masehi', 'jenis' => 'nasional'],
                ['tanggal' => '2026-01-23', 'keterangan' => 'Cuti Bersama Tahun Baru Imlek', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-01-24', 'keterangan' => 'Tahun Baru Imlek 2577', 'jenis' => 'nasional'],
                ['tanggal' => '2026-03-19', 'keterangan' => 'Hari Suci Nyepi Saka 1948', 'jenis' => 'nasional'],
                ['tanggal' => '2026-03-20', 'keterangan' => 'Cuti Bersama Nyepi', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-03-21', 'keterangan' => 'Cuti Bersama Nyepi', 'jenis' => 'cuti_bersama'],
                ['tanggal' => '2026-04-03', 'keterangan' => 'Wafat Isa Almasih', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-01', 'keterangan' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-14', 'keterangan' => 'Kenaikan Isa Almasih', 'jenis' => 'nasional'],
                ['tanggal' => '2026-05-15', 'keterangan' => 'Cuti Bersama Kenaikan Isa Almasih', 'jenis' => 'cuti_bersama'],
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