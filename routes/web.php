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
use App\Http\Middleware\PustakawanMiddleware;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| UTILITY ROUTE (PERBAIKAN STORAGE UNTUK HOSTING)
| Akses ini satu kali: perpustakaansdnberatwetan1.online/fix-storage
|--------------------------------------------------------------------------
*/
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
    Route::resource('anggotas', AnggotaController::class);
    Route::get('/anggotas/{anggota}/peminjaman', [AnggotaController::class, 'peminjaman'])->name('anggotas.peminjaman');
    Route::delete('/anggotas/delete-all', [AnggotaController::class, 'deleteAll'])->name('anggotas.delete-all');

    // ==================== MANAJEMEN PEMINJAMAN ====================
    Route::resource('pinjamans', PinjamanController::class);
    Route::post('/pinjamans/{id}/mark-returned', [PinjamanController::class, 'markAsReturned'])->name('pinjamans.mark-returned');

    // ==================== MANAJEMEN PENGEMBALIAN ====================
    Route::resource('pengembalians', PengembalianController::class);

    // ==================== LAPORAN ====================
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
    // 🔥 FIX: Menggunakan pesan teks biasa karena file view 'errors.404' tidak ditemukan
    return response('Halaman atau file gambar tidak ditemukan (404 Not Found).', 404);
});