<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class KatalogController extends Controller
{
    /**
     * Menampilkan Halaman Utama Katalog Y2K Futuristic
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter dropdown
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil Top 10 Buku Populer (Logic anti-error & Realtime Jumlah Pinjam)
        $popularBooks = Cache::remember('popular_books_katalog_y2k', 1800, function () {
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                
                // Gunakan Subquery agar total pinjam langsung menempel di data buku
                $popular = Book::with('kategori')
                    ->select('books.*', DB::raw('(SELECT COUNT(id) FROM pinjamans WHERE pinjamans.buku_id = books.id) as jumlah_dipinjam'))
                    ->orderByDesc('jumlah_dipinjam')
                    ->limit(10)
                    ->get();

                // Filter manual di level Collection agar aman dari error SQL Strict Mode
                // Cuma ambil buku yang jumlah pinjamnya lebih dari 0
                $filteredPopular = $popular->filter(function ($book) {
                    return $book->jumlah_dipinjam > 0;
                });

                if ($filteredPopular->isNotEmpty()) {
                    return $filteredPopular->values(); // Reset index array
                }
            }
            
            // Fallback: Jika belum ada data pinjaman, ambil berdasarkan stok terbanyak
            $fallback = Book::with('kategori')->orderBy('stok', 'DESC')->limit(10)->get();
            
            // Set default jumlah_dipinjam = 0 agar tidak error (kosong) di view
            foreach ($fallback as $book) {
                $book->jumlah_dipinjam = 0;
            }
            
            return $fallback;
        });

        // 3. Query Utama untuk Katalog (Grid Bawah)
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

        // Tampilkan buku terbaru (latest) dengan paginasi
        $books = $query->latest()->paginate(12)->withQueryString();

        return view('katalog.index', compact('books', 'kategoris', 'popularBooks'));
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