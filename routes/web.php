<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\HariLiburController;
use App\Http\Middleware\PustakawanMiddleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| UTILITY ROUTES (PERBAIKAN SERVER UNTUK HOSTING ONLINE)
|--------------------------------------------------------------------------
*/

// 1. Eksekusi tabel migrasi via web browser (Penyelamat Error 500 Hostinger)
// Akses sekali ke: https://perpustakaansdnberatwetan1.online/jalankan-migrasi-dong
Route::get('/jalankan-migrasi-dong', function () {
    try {
        Artisan::call('migrate');
        return "<h1>Sukses Berhasil!</h1><p>Struktur tabel 'hari_liburs' telah sukses dibuat di database MySQL Hostinger kamu.</p>";
    } catch (\Throwable $e) {
        return "<h1>Gagal Migrasi!</h1><p>Error: " . $e->getMessage() . "</p>";
    }
});

// 2. Jalur pintas menyuntikkan data kalender master 2026 (Bypass Tombol UI Macet)
// Akses sekali ke: https://perpustakaansdnberatwetan1.online/isi-libur-darurat
Route::get('/isi-libur-darurat', function () {
    try {
        // Array daftar hari libur nasional resmi tahun 2026 (Senin - Sabtu murni)
        $tanggalMerah = [
            '2026-01-01' => 'Tahun Baru 2026 Masehi',
            '2026-01-23' => 'Cuti Bersama Tahun Baru Imlek',
            '2026-01-24' => 'Tahun Baru Imlek 2577',
            '2026-03-19' => 'Hari Suci Nyepi Saka 1948',
            '2026-03-20' => 'Cuti Bersama Nyepi',
            '2026-03-21' => 'Cuti Bersama Nyepi',
            '2026-04-03' => 'Wafat Isa Almasih',
            '2026-05-01' => 'Hari Buruh Internasional',
            '2026-05-14' => 'Kenaikan Isa Almasih',
            '2026-05-15' => 'Cuti Bersama Kenaikan Isa Almasih',
            '2026-05-25' => 'Cuti Bersama Waisak',
            '2026-06-01' => 'Hari Lahir Pancasila',
            '2026-11-27' => 'Hari Raya Idul Adha 1447 H',
            '2026-12-25' => 'Hari Raya Natal',
        ];

        $inserted = 0;
        foreach ($tanggalMerah as $tgl => $ket) {
            $isCuti = str_contains(strtolower($ket), 'cuti') || str_contains(strtolower($ket), 'bersama');
            $jenis = $isCuti ? 'cuti_bersama' : 'nasional';

            \App\Models\HariLibur::updateOrCreate(
                ['tanggal' => $tgl],
                [
                    'keterangan' => $ket,
                    'jenis' => $jenis
                ]
            );
            $inserted++;
        }

        return "<h1>Berhasil Sukses!</h1><p>{$inserted} data master kalender 2026 berhasil disuntikkan ke database Hostinger Anda.</p>";
    } catch (\Throwable $e) {
        return "<h1>Gagal Mengisi Data!</h1><p>Error: " . $e->getMessage() . "</p>";
    }
});

// 3. Perbaikan folder penyimpanan gambar aset buku
// Akses sekali ke: https://perpustakaansdnberatwetan1.online/fix-storage
Route::get('/fix-storage', function () {
    // Karena hosting mematikan symlink, kita buat folder langsung di Document Root (public_html)
    $documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
    $target = $documentRoot . '/gambar_buku';

    try {
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
            return "<h1>Berhasil!</h1><p>Folder 'gambar_buku' telah dibuat langsung di server publik. Silakan upload buku baru.</p>";
        } else {
            return "<h1>Sudah Siap!</h1><p>Folder 'gambar_buku' sudah ada di server publik. Sistem siap digunakan untuk upload gambar.</p>";
        }
    } catch (\Throwable $e) {
        return "<h1>Gagal!</h1><p>Error: " . $e->getMessage() . "</p>";
    }
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tidak memerlukan login)
|--------------------------------------------------------------------------
*/

// Landing page / Halaman utama website
Route::view('/', 'landing')->name('landing');

// Katalog publik - untuk semua pengunjung
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/katalog/filter', [KatalogController::class, 'filter'])->name('katalog.filter');

// AJAX check ketersediaan buku
Route::get('/check-book', [PinjamanController::class, 'checkBook'])->name('check-book');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Login/Logout)
|--------------------------------------------------------------------------
*/

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Protected by Pustakawan Middleware)
| Hanya bisa diakses oleh pustakawan yang sudah login
|--------------------------------------------------------------------------
*/

Route::middleware([PustakawanMiddleware::class])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== MANAJEMEN BUKU ====================
    Route::resource('books', BookController::class);
    Route::get('/books/category/{id}', [BookController::class, 'byCategory'])->name('books.byCategory');

    // ==================== MANAJEMEN KATEGORI ====================
    Route::resource('categories', CategoryController::class);

    // ==================== MANAJEMEN ANGGOTA ====================
    // 1. Taruh Route Kustom Statis di Atas
Route::post('/anggotas/bulk-delete', [AnggotaController::class, 'bulkDelete'])->name('anggotas.bulkDelete');    Route::delete('/anggotas/delete-all', [AnggotaController::class, 'deleteAll'])->name('anggotas.delete-all');
    Route::get('/anggotas/{anggota}/peminjaman', [AnggotaController::class, 'peminjaman'])->name('anggotas.peminjaman');

    // 2. Taruh Route Resource di Paling Bawah agar Tidak Bentrok dengan URL di Atas
    Route::resource('anggotas', AnggotaController::class);

    // ==================== MANAJEMEN PEMINJAMAN ====================
    Route::resource('pinjamans', PinjamanController::class);
    Route::get('/pinjamans/get-anggota/{nisn}', [PinjamanController::class, 'getAnggotaByNisn'])->name('pinjamans.get-anggota');
    Route::post('/pinjamans/{id}/mark-returned', [PinjamanController::class, 'markAsReturned'])->name('pinjamans.mark-returned');

    // ==================== MANAJEMEN PENGEMBALIAN ====================
    Route::resource('pengembalians', PengembalianController::class);

    // ==================== DB MASTER HARI LIBUR NASIONAL ====================
    Route::get('/hari-liburs', [HariLiburController::class, 'index'])->name('hari-liburs.index');
    Route::post('/hari-liburs', [HariLiburController::class, 'store'])->name('hari-liburs.store');
    Route::put('/hari-liburs/{id}', [HariLiburController::class, 'update'])->name('hari-liburs.update');
    Route::delete('/hari-liburs/{id}', [HariLiburController::class, 'destroy'])->name('hari-liburs.destroy');
    Route::post('/hari-liburs/generate', [HariLiburController::class, 'generateTahun'])->name('hari-liburs.generate');

    // ==================== LAPORAN PERPUSTAKAAN ====================
    Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
        // Laporan Peminjaman
        Route::get('/peminjaman', 'laporanPeminjaman')->name('peminjaman');
        Route::get('/peminjaman/export', 'exportPeminjaman')->name('peminjaman.export');

        // Laporan Buku
        Route::get('/buku', 'laporanBuku')->name('buku');
        Route::get('/buku/export', 'exportBuku')->name('buku.export');

        // Laporan Pengembalian
        Route::get('/pengembalian', 'laporanPengembalian')->name('pengembalian');
        Route::get('/pengembalian/export', 'exportPengembalian')->name('pengembalian.export');
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE (Halaman 404 Not Found)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    // FIX: Menggunakan pesan teks biasa karena file view 'errors.404' tidak ditemukan
    return response('Halaman atau file gambar tidak ditemukan (404 Not Found).', 404);
});