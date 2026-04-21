<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Tampilkan semua buku dengan fitur pencarian dan filter
     */
    public function index(Request $request)
    {
        // 1. Ambil data kategori untuk dropdown
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil data buku populer (Anti-Error SQL)
        $popularBooks = $this->getPopularBooks(10);

        // 3. Query Utama untuk Grid/Daftar Buku
        $query = Book::with('kategori')->withCount(['pinjamans as total_dipinjam' => function($q) {
            $q->where('status', 'sudah dikembalikan');
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', '%' . $search . '%')
                  ->orWhere('penulis', 'LIKE', '%' . $search . '%')
                  ->orWhere('penerbit', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $books = $query->latest()->paginate(24)->withQueryString();

        return view('books.index', compact('books', 'kategoris', 'popularBooks'));
    }

    /**
     * Helper Method: Ambil buku terpopuler (Tanpa GROUP BY manual agar tidak error)
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_admin_safe_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function() use ($limit) {
            return Book::with('kategori')
                ->withCount(['pinjamans as total_dipinjam' => function($q) {
                    $q->where('status', 'sudah dikembalikan');
                }])
                ->having('total_dipinjam', '>', 0)
                ->orderBy('total_dipinjam', 'desc')
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
            $query = Book::with('kategori')->withCount(['pinjamans as total_dipinjam' => function($q) {
                $q->where('status', 'sudah dikembalikan');
            }]);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
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

    /**
     * Simpan buku baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'  => 'required|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $path = $file->store('buku', 'public');
            $validated['cover'] = $path;
        }

        Book::create($validated);
        Cache::forget('popular_books_admin_safe_10'); // Reset cache biar data baru masuk

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function create()
    {
        $kategoris = Category::all();
        return view('books.create', compact('kategoris'));
    }

    public function edit(Book $book)
    {
        $kategoris = Category::all();
        return view('books.edit', compact('book', 'kategoris'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'  => 'required|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover) { Storage::disk('public')->delete($book->cover); }
            $validated['cover'] = $request->file('cover')->store('buku', 'public');
        }

        $book->update($validated);
        Cache::forget('popular_books_admin_safe_10');

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) { Storage::disk('public')->delete($book->cover); }
        $book->delete();
        Cache::forget('popular_books_admin_safe_10');
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}