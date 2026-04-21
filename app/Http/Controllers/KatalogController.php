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
        // Ambil semua kategori
        $kategoris = Category::all();

        // ==================== REKOMENDASI BUKU POPULER ====================
        // Ambil 10 buku paling sering dipinjam (diurutkan dari tertinggi ke terendah)
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

        // Paginate
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
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            // Cek apakah tabel pinjamans ada
            if (!$this->hasPinjamanTable()) {
                return collect();
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
                ->orderBy('total_dipinjam', 'DESC') // 🔥 URUTAN DARI TERBANYAK KE TERSEDIKIT
                ->limit($limit)
                ->get();

            // Jika tidak ada data peminjaman sama sekali, tampilkan berdasarkan stok terbanyak
            if ($popularBooks->isEmpty() || $popularBooks->sum('total_dipinjam') == 0) {
                return Book::with('kategori')
                    ->orderBy('stok', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->map(function($book) {
                        $book->total_dipinjam = 0;
                        return $book;
                    });
            }

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
            return false;
        }
    }

    /**
     * Clear popular books cache.
     */
    public function clearRecommendationCache()
    {
        Book::clearPopularBooksCache();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cache rekomendasi berhasil dihapus']);
        }
        
        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }

    /**
     * Fungsi AJAX Filter untuk Pencarian Live di UI Baru
     */
    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}