<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{
    /**
     * Display a listing of the books with recommendations.
     */
    public function index(Request $request)
    {
        // Ambil semua kategori
        $kategoris = Category::all();

        // ==================== REKOMENDASI BUKU POPULER ====================
        // Ambil daftar buku paling banyak dipinjam
        $popularBooks = $this->getPopularBooks(10);

        // ==================== QUERY KATALOG BUKU ====================
        $query = Book::with('kategori');

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Paginate 24 buku per halaman
        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Get popular books based on borrowing history from pinjamans table.
     * Diurutkan dari yang paling banyak dipinjam ke yang paling sedikit
     * Buku yang belum dipinjam tidak akan masuk rekomendasi
     * 
     * @param int $limit Jumlah buku yang ditampilkan (default 10)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($limit) {
            // Cek apakah tabel pinjamans ada
            if (!$this->hasPinjamanTable()) {
                Log::warning('Tabel pinjamans tidak ditemukan.');
                return collect(); // kosong, bukan fallback dummy
            }

            // Query untuk mendapatkan buku paling sering dipinjam dari data peminjaman
            $popularBooks = Book::with('kategori')
                ->join('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                ->select(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.cover',
                    'books.stok',
                    'books.kategori_id',
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                ->where('pinjamans.status', 'sudah dikembalikan') // Hanya yang sudah dikembalikan
                ->groupBy(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.cover',
                    'books.stok',
                    'books.kategori_id'
                )
                ->orderByDesc('total_dipinjam')
                ->limit($limit)
                ->get();

            // Jika hasil query kosong, kembalikan koleksi kosong
            return $popularBooks;
        });
    }

    /**
     * Check if pinjamans table exists.
     */
    private function hasPinjamanTable()
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('pinjamans');
        } catch (\Exception $e) {
            Log::error('Error checking pinjamans table: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fungsi AJAX Filter (Tanpa Refresh Halaman)
     */
    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('penulis', 'LIKE', "%{$search}%")
                      ->orWhere('penerbit', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('kategori')) {
                $query->where('kategori_id', $request->kategori);
            }

            $books = $query->latest()->get();

            return response()->json([
                'success' => true,
                'books'   => $books
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear popular books cache.
     */
    public function clearRecommendationCache()
    {
        Cache::forget('popular_books_limit_10');
        Cache::forget('popular_books_limit_5');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cache rekomendasi berhasil dihapus'
            ]);
        }

        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }
}