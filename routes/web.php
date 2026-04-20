<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KatalogController;
use App\Http\Middleware\PustakawanMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================================
// 🔹 PUBLIC ROUTES (Tanpa Login)
// =============================================

// Halaman Landing (Homepage)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Katalog Buku Publik
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

// Check ketersediaan buku (AJAX)
Route::get('/check-book', [PinjamanController::class, 'checkBook'])->name('check-book');

// =============================================
// 🔹 AUTHENTICATION ROUTES
// =============================================
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// =============================================
// 🔒 ADMIN ROUTES (Hanya untuk Pustakawan)
// =============================================
Route::middleware([PustakawanMiddleware::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Book Management
    Route::resource('books', BookController::class);
    Route::get('/books/category/{id}', [BookController::class, 'byCategory'])->name('books.byCategory');

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Member Management
    Route::resource('anggotas', AnggotaController::class);
    Route::get('/anggotas/{anggota}/peminjaman', [AnggotaController::class, 'peminjaman'])->name('anggotas.peminjaman');
    Route::delete('/anggotas/delete-all', [AnggotaController::class, 'deleteAll'])->name('anggotas.delete-all');

    // Loan Management
    Route::resource('pinjamans', PinjamanController::class);
    Route::post('/pinjamans/{id}/mark-returned', [PinjamanController::class, 'markAsReturned'])->name('pinjamans.mark-returned');
    Route::get('/pinjamans/get-book-stock', [PinjamanController::class, 'getBookStock'])->name('pinjamans.get-book-stock');

    // Return Management
    Route::resource('pengembalians', PengembalianController::class);

    // Report Routes
    Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
        Route::get('/peminjaman', 'laporanPeminjaman')->name('peminjaman');
        Route::get('/peminjaman/export', 'exportPeminjaman')->name('peminjaman.export');
        Route::get('/buku', 'laporanBuku')->name('buku');
        Route::get('/buku/export', 'exportBuku')->name('buku.export');
        Route::get('/pengembalian', 'laporanPengembalian')->name('pengembalian');
        Route::get('/pengembalian/export', 'exportPengembalian')->name('pengembalian.export');
    });

    // =============================================
    // 📚 RAK BUKU MANAGEMENT (Bookshelf)
    // =============================================
    Route::prefix('rak')->name('rak.')->controller(RakController::class)->group(function () {
        
        // View Routes (Halaman utama)
        Route::get('/', 'index')->name('index');
        
        // CRUD Operations
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        
        // Category Management in Rak
        Route::post('/{rakId}/add-category', 'addCategory')->name('addCategory');
        Route::delete('/{rakId}/remove-category/{kategoriId}', 'removeCategory')->name('removeCategory');
        
        // ========== API ROUTES FOR AJAX (Data dinamis) ==========
        // Get books with filters (search & category) - UTAMA untuk AJAX
        Route::get('/books', 'getBooks')->name('books');
        
        // Get books by specific rak (alternatif)
        Route::get('/{rakId}/books', 'getBooksByRak')->name('getBooks');
        
        // Get books by category for filtering
        Route::get('/api/categories/{categoryId}/books', 'getCategoryBooks')->name('api.category.books');
    });
});

// =============================================
// 🔹 FALLBACK ROUTE (404)
// =============================================
Route::fallback(function () {
    return view('errors.404');
});