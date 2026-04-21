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
    public function index(Request $request)
    {
        $kategoris = Category::all();

        // 🔥 rekomendasi
        $popularBooks = $this->getPopularBooks(10);

        $query = Book::with('kategori');

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', compact('books', 'kategoris', 'popularBooks'));
    }

    // 🔥 FIX DI SINI
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {

            if (!$this->hasPinjamanTable()) {
                return $this->getFallbackPopularBooks($limit);
            }

            $totalPinjaman = DB::table('pinjamans')
                ->where('status', 'sudah dikembalikan')
                ->count();

            if ($totalPinjaman == 0) {
                return $this->getFallbackPopularBooks($limit);
            }

            return Book::with('kategori')
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
                    'books.cover', // 🔥 FIX DI SINI
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
                    'books.cover', // 🔥 FIX DI SINI
                    'books.stok',
                    'books.kategori_id'
                )
                ->orderByDesc('total_dipinjam')
                ->limit($limit)
                ->get();
        });
    }

    private function getFallbackPopularBooks($limit = 10)
    {
        return Book::with('kategori')
            ->orderByDesc('stok')
            ->limit($limit)
            ->get()
            ->map(function($book) {
                $book->total_dipinjam = 0;
                return $book;
            });
    }

    private function hasPinjamanTable()
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('pinjamans');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    // AJAX filter
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
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function clearRecommendationCache()
    {
        Cache::flush(); // 🔥 lebih simpel

        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }
}