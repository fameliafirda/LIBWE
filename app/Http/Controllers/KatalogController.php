<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    /**
     * Display a listing of the books with recommendations.
     */
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $kategoris = Category::all();

        // ==================== REKOMENDASI BUKU POPULER ====================
        // Ambil 10 buku paling sering dipinjam (diurutkan dari tertinggi ke terendah)
        $popularBooks = $this->getPopularBooks(10);

        // ==================== QUERY KATALOG BUKU ====================
        $query = Book::with('kategori');

        // Pencarian berdasarkan judul, penulis, atau penerbit
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Paginate (12 buku per halaman)
        $books = $query->latest()->paginate(12)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Filter books via AJAX untuk pencarian dan filter real-time
     */
    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            // Search berdasarkan judul, penulis, atau penerbit
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', '%'.$search.'%')
                      ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                      ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
                });
            }

            // Filter berdasarkan kategori
            if ($request->filled('kategori') && $request->kategori != '') {
                $query->where('kategori_id', $request->kategori);
            }

            // Ambil semua data (tanpa pagination untuk AJAX)
            $books = $query->latest()->get();

            return response()->json([
                'success' => true,
                'books' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get popular books based on borrowing history from pinjamans table.
     * Diurutkan dari yang paling banyak dipinjam ke yang paling sedikit
     * 
     * @param int $limit Jumlah buku yang ditampilkan (default 10)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            // Cek apakah tabel pinjamans ada
            if (!$this->hasPinjamanTable()) {
                return $this->getDefaultPopularBooks($limit);
            }

            // Query untuk mendapatkan buku paling sering dipinjam
            $popularBooks = Book::with('kategori')
                ->leftJoin('pinjamans', function($join) {
                    $join->on('books.id', '=', 'pinjamans.buku_id')
                         ->where('pinjamans.status', '=', 'sudah dikembalikan');
                })
                ->select(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.gambar',
                    'books.stok',
                    'books.kategori_id',
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                ->groupBy(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.gambar',
                    'books.stok',
                    'books.kategori_id'
                )
                ->orderBy('total_dipinjam', 'DESC')
                ->limit($limit)
                ->get();

            // Jika tidak ada data peminjaman sama sekali, tampilkan berdasarkan stok terbanyak
            if ($popularBooks->isEmpty() || $popularBooks->sum('total_dipinjam') == 0) {
                return $this->getDefaultPopularBooks($limit);
            }

            return $popularBooks;
        });
    }

    /**
     * Get default popular books (by stock) when no borrowing data exists
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDefaultPopularBooks($limit)
    {
        return Book::with('kategori')
            ->orderBy('stok', 'DESC')
            ->limit($limit)
            ->get()
            ->map(function($book) {
                $book->total_dipinjam = 0;
                return $book;
            });
    }

    /**
     * Check if pinjamans table exists in database
     * 
     * @return bool
     */
    private function hasPinjamanTable()
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('pinjamans');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear popular books cache
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function clearRecommendationCache()
    {
        // Hapus cache untuk limit 10 dan 5
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