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
        // Mengambil 10 buku yang paling sering dipinjam
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
     * Get popular books based on borrowing history.
     * Semakin banyak dipinjam, semakin atas urutannya.
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            // Cek apakah tabel pinjamans ada
            if (!$this->hasPinjamanTable()) {
                return $this->getFallbackPopularBooks($limit);
            }

            // Cek apakah ada data peminjaman yang sudah dikembalikan
            $totalPinjaman = DB::table('pinjamans')
                ->where('status', 'sudah dikembalikan')
                ->count();

            if ($totalPinjaman == 0) {
                return $this->getFallbackPopularBooks($limit);
            }

            // Query Top 10 berdasarkan jumlah terbanyak di tabel peminjaman
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
                    DB::raw('COALESCE(COUNT(pinjamans.id), 0) as total_dipinjam')
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
                ->orderBy('total_dipinjam', 'DESC') // Urutkan dari yang terbanyak
                ->limit($limit)
                ->get();

            if ($popularBooks->isEmpty() || $popularBooks->sum('total_dipinjam') == 0) {
                return $this->getFallbackPopularBooks($limit);
            }

            return $popularBooks;
        });
    }

    /**
     * Fallback: Ambil buku berdasarkan stok terbanyak jika belum ada yang pinjam
     */
    private function getFallbackPopularBooks($limit = 10)
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
     * Fungsi AJAX Filter (Tanpa Refresh Halaman)
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
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Clear popular books cache.
     */
    public function clearRecommendationCache()
    {
        Cache::forget('popular_books_limit_10');
        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }
}