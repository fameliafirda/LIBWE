<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class KatalogController extends Controller
{
    /**
     * Menampilkan Halaman Utama Katalog
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter dropdown
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil Top 10 Buku Populer
        // Menggunakan withCount('pinjamans') akan otomatis membuat variabel 'pinjamans_count'
        $popularBooks = Book::with('kategori')
            ->withCount('pinjamans')
            ->orderBy('pinjamans_count', 'desc')
            ->limit(10)
            ->get();

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