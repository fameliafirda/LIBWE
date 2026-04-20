<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Category::all();
        $popularBooks = $this->getPopularBooks(10);

        $query = Book::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', '%'.$search.'%')
                      ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                      ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
                });
            }

            if ($request->filled('kategori') && $request->kategori != '') {
                $query->where('kategori_id', $request->kategori);
            }

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

    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            if (!$this->hasPinjamanTable()) {
                return $this->getDefaultPopularBooks($limit);
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

            if ($popularBooks->isEmpty() || $popularBooks->sum('total_dipinjam') == 0) {
                return $this->getDefaultPopularBooks($limit);
            }

            return $popularBooks;
        });
    }

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

    private function hasPinjamanTable()
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('pinjamans');
        } catch (\Exception $e) {
            return false;
        }
    }

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