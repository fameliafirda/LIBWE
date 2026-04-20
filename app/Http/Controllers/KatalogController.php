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
        // 1. Ambil data kategori untuk filter
        $kategoris = Category::all();

        // 2. Ambil Top 10 Buku Populer
        $popularBooks = $this->getPopularBooks(10);

        // 3. Query Utama untuk Katalog Bawah
        $query = Book::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Tampilkan yang terbaru dan paginate 12 buku per halaman
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
                      ->orWhere('penulis', 'LIKE', '%'.$search.'%');
                });
            }

            if ($request->filled('kategori')) {
                $query->where('kategori_id', $request->kategori);
            }

            return response()->json([
                'success' => true,
                'books' => $query->latest()->get()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_v1_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() use ($limit) {
            // Cek apakah tabel pinjaman ada
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                $popular = Book::select('books.*', DB::raw('COUNT(pinjamans.id) as total_dipinjam'))
                    ->leftJoin('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                    ->groupBy('books.id')
                    ->orderBy('total_dipinjam', 'DESC')
                    ->limit($limit)
                    ->get();

                // Jika ada data peminjaman, kembalikan
                if ($popular->sum('total_dipinjam') > 0) {
                    return $popular;
                }
            }

            // Jika belum ada data pinjaman, ambil berdasarkan stok terbanyak sebagai default
            return Book::orderBy('stok', 'DESC')->limit($limit)->get();
        });
    }
}