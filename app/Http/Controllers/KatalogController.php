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
     * Menampilkan halaman katalog utama.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk dropdown filter
        $kategoris = Category::all();

        // 2. Ambil Rekomendasi (Hanya yang pernah dipinjam > 0)
        $popularBooks = $this->getPopularBooks(10);

        // 3. Query Utama untuk Katalog Grid
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

        // Paginate 24 buku (sesuai permintaan fitur tidak dikurangi)
        $books = $query->latest()->paginate(24)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Logika mengambil buku populer: Minimal 1x dipinjam.
     */
    private function getPopularBooks($limit = 10)
    {
        // Gunakan cache agar performa cepat, tapi durasi pendek agar data tetap update
        $cacheKey = 'popular_books_katalog_realtime_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function() use ($limit) {
            if (!DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                return collect();
            }

            return Book::with('kategori')
                ->join('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                ->select(
                    'books.id', 'books.judul', 'books.penulis', 'books.penerbit',
                    'books.tahun_terbit', 'books.gambar', 'books.stok', 'books.kategori_id',
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                // Filter hanya yang sudah dikembalikan agar data valid
                ->where('pinjamans.status', '=', 'sudah dikembalikan')
                ->groupBy('books.id', 'books.judul', 'books.penulis', 'books.penerbit', 'books.tahun_terbit', 'books.gambar', 'books.stok', 'books.kategori_id')
                ->having('total_dipinjam', '>', 0) // 🔥 SYARAT: Tidak muncul jika 0
                ->orderBy('total_dipinjam', 'DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Fitur AJAX Filter (Pencarian Live)
     */
    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('penulis', 'LIKE', "%{$search}%");
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