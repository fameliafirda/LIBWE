<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Rak;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Category::all();

        // ✅ FIX: tambahin selectedRak biar view gak error
        $selectedRak = null;

        // kalau nanti kamu pakai filter rak, ini sudah siap
        if ($request->filled('rak')) {
            $selectedRak = Rak::find($request->rak);
        }

        $popularBooks = $this->getPopularBooks(10);

        $query = Book::with('kategori');

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%$search%")
                  ->orWhere('penulis', 'LIKE', "%$search%")
                  ->orWhere('penerbit', 'LIKE', "%$search%");
            });
        }

        // kategori filter
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // optional filter rak (kalau belum dipakai juga aman)
        if ($request->filled('rak')) {
            $query->where('rak_id', $request->rak);
        }

        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,

            // 🔥 FIX UTAMA
            'selectedRak' => $selectedRak ?? null,
        ]);
    }

    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_limit_' . $limit;

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($limit) {

            if (!DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                return collect();
            }

            $popularBooks = Book::with('kategori')
                ->leftJoin('pinjamans', function ($join) {
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
                return Book::with('kategori')
                    ->orderBy('stok', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->map(function ($book) {
                        $book->total_dipinjam = 0;
                        return $book;
                    });
            }

            return $popularBooks;
        });
    }

    public function clearRecommendationCache()
    {
        Cache::forget('popular_books_limit_10');

        return back()->with('success', 'Cache berhasil dihapus');
    }
}