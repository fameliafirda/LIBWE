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
        // Ambil data untuk tahun berjalan dan tahun depan (contoh: 2026)
        $tahunSekarang = Carbon::now()->format('Y');
        
        $this->fetchAndSaveFromApi($tahunSekarang);
    }

    /**
     * Fungsi mengambil data dari API eksternal kalender Indonesia
     */
    private function fetchAndSaveFromApi($year)
    {
        // Menggunakan API Day Off publik yang stabil untuk kalender Indonesia
        $url = "https://dayoffapi.vercel.app/api?year=" . $year;

        try {
            $response = @file_get_contents($url);

            if ($response) {
                $data = json_decode($response, true);

                if (is_array($data)) {
                    foreach ($data as $row) {
                        if (isset($row['tanggal'])) {
                            // Deteksi apakah cuti bersama berdasarkan teks keterangan
                            $keterangan = $row['keterangan'] ?? 'Hari Libur';
                            $isCuti = str_contains(strtolower($keterangan), 'cuti') || str_contains(strtolower($keterangan), 'bersama');
                            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

                            // Simpan ke database jika belum ada, jika sudah ada akan di-update (mencegah duplikat)
                            HariLibur::updateOrCreate(
                                ['tanggal' => $row['tanggal']],
                                [
                                    'keterangan' => $keterangan,
                                    'jenis' => $jenis
                                ]
                            );
                        }
                    }
                    $this->command->info("Berhasil menyinkronkan database Kalender Indonesia untuk tahun {$year}!");
                }
            } else {
                $this->command->error("Gagal menghubungi API Kalender Indonesia. Menggunakan data cadangan standard.");
                $this->insertFallbackData($year);
            }
        } catch (\Exception $e) {
            $this->insertFallbackData($year);
        }
    }

    /**
     * Data cadangan resmi 2026 jika sewaktu-waktu server API eksternal mati/down
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