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

        // 2. Ambil Top 10 Buku Populer (Logic anti-error SQLStrict)
        $popularBooks = Cache::remember('popular_books_katalog_y2k', 1800, function () {
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                // Cari ID buku yang paling banyak dipinjam
                $popularIds = DB::table('pinjamans')
                    ->select('buku_id', DB::raw('COUNT(id) as total'))
                    ->groupBy('buku_id')
                    ->orderBy('total', 'DESC')
                    ->limit(10)
                    ->pluck('buku_id');

                if ($popularIds->isNotEmpty()) {
                    // Ambil detail buku dan jaga urutan rankingnya
                    $idsOrder = $popularIds->implode(',');
                    return Book::with('kategori')
                        ->whereIn('id', $popularIds)
                        ->orderByRaw("FIELD(id, $idsOrder)")
                        ->get();
                }
            }
            // Fallback: Jika belum ada data pinjaman, ambil berdasarkan stok terbanyak
            return Book::with('kategori')->orderBy('stok', 'DESC')->limit(10)->get();
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