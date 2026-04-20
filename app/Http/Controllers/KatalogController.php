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
     * Menampilkan Halaman Utama Katalog Futuristik
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk dropdown filter
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil Top 10 Buku Paling Populer (Berdasarkan data peminjaman real)
        // Kita simpan di Cache selama 30 menit agar database tidak berat
        $popularBooks = Cache::remember('popular_books_katalog', 1800, function () {
            // Cek apakah tabel pinjamans ada untuk menghindari error
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                return Book::leftJoin('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                    ->select('books.*', DB::raw('COUNT(pinjamans.id) as total_pinjam'))
                    ->groupBy('books.id')
                    ->orderBy('total_pinjam', 'DESC')
                    ->limit(10)
                    ->get();
            }
            // Fallback jika belum ada data peminjaman, ambil berdasarkan stok terbanyak
            return Book::orderBy('stok', 'DESC')->limit(10)->get();
        });

        // 3. Logika Query untuk Katalog Utama (Grid Bawah)
        $query = Book::with('kategori');

        // Filter Pencarian Judul/Penulis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('penulis', 'LIKE', "%{$search}%");
            });
        }

        // Filter Berdasarkan Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Tampilkan buku terbaru (latest) dengan paginasi 12 buku
        $books = $query->latest()->paginate(12)->withQueryString();

        // Kirim data ke view
        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks
        ]);
    }

    /**
     * Fungsi AJAX Filter (Tanpa Refresh Halaman)
     * Ini yang bikin tampilan terasa canggih/futuristik
     */
    public function filter(Request $request)
    {
        try {
            $query = Book::with('kategori');

            // Logika pencarian yang sama dengan index
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

            // Ambil semua data hasil filter
            $books = $query->latest()->get();

            // Return dalam format JSON untuk diproses JavaScript di view
            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'books'   => $books
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}