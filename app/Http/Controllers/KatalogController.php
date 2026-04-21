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
     * Tampilkan daftar buku dengan rekomendasi
     */
    public function index(Request $request)
    {
        // Ambil semua kategori
        $kategoris = Category::all();

        // ==================== REKOMENDASI BUKU POPULER ====================
        $popularBooks = $this->getPopularBooks(10); // Ambil 10 buku paling populer

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

        // Paginate 24 buku per halaman
        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Mengambil buku yang paling populer berdasarkan histori peminjaman.
     * Diurutkan berdasarkan yang paling banyak dipinjam
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
                Log::info('Tabel pinjamans tidak ditemukan, menggunakan fallback stok terbanyak');
                return $this->getFallbackPopularBooks($limit);
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
                    'books.cover',
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
                    'books.cover',
                    'books.stok',
                    'books.kategori_id'
                )
                ->orderBy('total_dipinjam', 'DESC')
                ->limit($limit)
                ->get();

            // Jika hasil query kosong atau total peminjaman lebih sedikit, fallback
            if ($popularBooks->isEmpty() || $popularBooks->sum('total_dipinjam') == 0) {
                return $this->getFallbackPopularBooks($limit);
            }

            // Filter buku yang tidak dipinjam sama sekali
            return $popularBooks->filter(function($book) {
                return $book->total_dipinjam > 0; // Buku harus dipinjam minimal sekali
            });
        });
    }

    /**
     * Fallback: Ambil buku berdasarkan stok terbanyak jika tidak ada peminjaman
     */
    private function getFallbackPopularBooks($limit = 10)
    {
        return Book::with('kategori')
            ->orderBy('stok', 'DESC')
            ->limit($limit)
            ->get()
            ->map(function($book) {
                $book->total_dipinjam = 0;  // Set total peminjaman menjadi 0 pada fallback
                return $book;
            });
    }

    /**
     * Cek apakah tabel pinjamans ada
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
            return response()->json(['success' => true, 'message' => 'Cache rekomendasi berhasil dihapus']);
        }
        
        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }
}