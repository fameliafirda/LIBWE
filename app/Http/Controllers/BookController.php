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
     * Menampilkan semua buku dengan fitur pencarian dan filter
     */
    public function index(Request $request)
    {
        // 1. Ambil data kategori (Nama variabel harus $kategoris sesuai Blade)
        $kategoris = Category::orderBy('nama', 'asc')->get();

        // 2. Ambil data buku populer (Hanya yang pernah dipinjam)
        $popularBooks = $this->getPopularBooks(10);

        // 3. Query Utama untuk Grid/Daftar Buku
        $query = Book::with('kategori');

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
     * Form tambah buku
     */
    public function create()
    {
        $kategoris = Category::all();
        return view('books.create', compact('kategoris'));
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
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('buku', $filename, 'public');

            $validated['gambar'] = $path; // Simpan ke kolom 'gambar'
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Form edit buku
     */
    public function edit(Book $book)
    {
        $kategoris = Category::all();
        return view('books.edit', compact('book', 'kategoris'));
    }

    /**
     * Update data buku
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'  => 'required|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($book->gambar && Storage::disk('public')->exists($book->gambar)) {
                Storage::disk('public')->delete($book->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('buku', $filename, 'public');

            $validated['gambar'] = $path;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku
     */
    public function destroy(Book $book)
    {
        if ($book->gambar && Storage::disk('public')->exists($book->gambar)) {
            Storage::disk('public')->delete($book->gambar);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
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
     * Helper Method: Logika mengambil buku populer (Real-time)
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_admin_realtime_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function() use ($limit) {
            if (!DB::connection()->getSchemaBuilder()->hasTable('pinjamans')) {
                return collect();
            }

            return Book::with('kategori')
                ->join('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                ->select(
                    'books.*', 
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                ->where('pinjamans.status', '=', 'sudah dikembalikan')
                ->groupBy(
                    'books.id', 'books.judul', 'books.penulis', 'books.penerbit', 
                    'books.tahun_terbit', 'books.gambar', 'books.stok', 
                    'books.kategori_id', 'books.created_at', 'books.updated_at'
                )
                ->having('total_dipinjam', '>', 0)
                ->orderBy('total_dipinjam', 'DESC')
                ->limit($limit)
                ->get();
        });
    }
}