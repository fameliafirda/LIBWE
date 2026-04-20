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

        // 2. Ambil Top 10 Buku Populer (Logika diperbaiki agar tidak SQL Error)
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

        return view('katalog.index', compact('books', 'kategoris', 'popularBooks'));
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
            // Cek apakah tabel pinjamans ada
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                
                // Ambil ID buku yang paling banyak dipinjam
                $popularIds = DB::table('pinjamans')
                    ->select('buku_id', DB::raw('COUNT(id) as total'))
                    ->groupBy('buku_id')
                    ->orderBy('total', 'DESC')
                    ->limit($limit)
                    ->pluck('buku_id');

                if ($popularIds->isNotEmpty()) {
                    // Ambil detail buku berdasarkan ID tersebut dan jaga urutannya
                    $idsOrder = $popularIds->implode(',');
                    return Book::whereIn('id', $popularIds)
                        ->orderByRaw("FIELD(id, $idsOrder)")
                        ->get();
                }
            }

            // Fallback: Jika belum ada data pinjaman, ambil berdasarkan stok terbanyak
            return Book::orderBy('stok', 'DESC')->limit($limit)->get();
        });
    }
}