<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk dropdown filter
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil Top 10 Buku Populer (Logic diperbaiki untuk menghindari Error 1055)
        $popularBooks = Cache::remember('popular_books_katalog', 1800, function () {
            if (DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                
                // Langkah A: Cari ID buku yang paling banyak dipinjam
                $popularIds = DB::table('pinjamans')
                    ->select('buku_id', DB::raw('COUNT(id) as total_pinjam'))
                    ->groupBy('buku_id')
                    ->orderBy('total_pinjam', 'DESC')
                    ->limit(10)
                    ->pluck('buku_id');

                if ($popularIds->isNotEmpty()) {
                    // Langkah B: Ambil detail buku berdasarkan ID tersebut
                    // Menggunakan orderByRaw agar urutan ranking (1-10) tidak berantakan
                    $idsOrder = $popularIds->implode(',');
                    return Book::whereIn('id', $popularIds)
                        ->orderByRaw("FIELD(id, $idsOrder)")
                        ->get();
                }
            }
            
            // Fallback jika belum ada data pinjaman
            return Book::orderBy('stok', 'DESC')->limit(10)->get();
        });

        // 3. Query Utama untuk Katalog (Data dari Pustakawan)
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

        // Ambil data terbaru yang diinput pustakawan
        $books = $query->latest()->paginate(12)->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks
        ]);
    }

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
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}