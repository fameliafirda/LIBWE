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
     * Tampilkan daftar buku dengan rekomendasi data real
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter dropdown
        $kategoris = Category::all();

        // 2. REKOMENDASI BUKU (Data Real: Diurutkan berdasarkan jumlah pinjaman terbanyak)
        $popularBooks = $this->getPopularBooks(10);

        // 3. QUERY KATALOG UTAMA
        // Menghitung jumlah dipinjam secara real-time via subquery agar performa tetap ringan
        $pinjamanSubquery = DB::table('pinjamans')
            ->select('buku_id', DB::raw('COUNT(*) as total_dipinjam'))
            ->groupBy('buku_id');

        $query = Book::with('kategori');

        // Pencarian (Judul atau Penulis)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Join ke subquery untuk mendapatkan field total_dipinjam
        $books = $query->leftJoinSub($pinjamanSubquery, 'pinjaman_count', function ($join) {
            $join->on('books.id', '=', 'pinjaman_count.buku_id');
        })
        ->select(
            'books.*',
            DB::raw('COALESCE(pinjaman_count.total_dipinjam, 0) as total_dipinjam')
        )
        ->orderByDesc('books.created_at') // Terbaru masuk duluan
        ->paginate(24)
        ->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Logic Rekomendasi: Semakin sering dipinjam, semakin di depan
     */
    private function getPopularBooks($limit = 10)
    {
        // Cache selama 30 menit agar database tidak terbebani setiap page load
        return Cache::remember('popular_books_real_system', now()->addMinutes(30), function () use ($limit) {
            return Book::with('kategori')
                ->join('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                ->select(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.cover',
                    'books.stok',
                    'books.kategori_id',
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                ->groupBy('books.id', 'books.judul', 'books.penulis', 'books.cover', 'books.stok', 'books.kategori_id')
                ->orderByDesc('total_dipinjam')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * AJAX Filter untuk pencarian tanpa reload (opsional jika dipakai di JS)
     */
    public function filter(Request $request)
    {
        try {
            $pinjamanSubquery = DB::table('pinjamans')
                ->select('buku_id', DB::raw('COUNT(*) as total_dipinjam'))
                ->groupBy('buku_id');

            $query = Book::with('kategori')
                ->leftJoinSub($pinjamanSubquery, 'pinjaman_count', function ($join) {
                    $join->on('books.id', '=', 'pinjaman_count.buku_id');
                })
                ->select('books.*', DB::raw('COALESCE(pinjaman_count.total_dipinjam, 0) as total_dipinjam'));

            if ($request->filled('search')) {
                $query->where('books.judul', 'LIKE', "%{$request->search}%");
            }

            return response()->json([
                'success' => true,
                'books' => $query->get()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}