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
     * Menampilkan halaman katalog dengan rekomendasi dan grid buku.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk dropdown
        $kategoris = Category::all();

        // 2. Ambil 10 buku paling sering dipinjam (Rekomendasi)
        $popularBooks = $this->getPopularBooks(10);

        // 3. Query Utama untuk Katalog Buku (Grid)
        $query = Book::with('kategori');

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // Fitur Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Pagination 24 buku per halaman
        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Logika mengambil buku populer berdasarkan history peminjaman.
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            if (!$this->hasPinjamanTable()) {
                return collect();
            }

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

            // Jika belum ada data pinjam, ambil berdasarkan stok terbanyak (Fallback)
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}