<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Tampilkan semua buku dengan fitur pencarian dan filter
     */
    public function index(Request $request)
    {
        $query = Book::with('kategori')->latest();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Tampilkan buku berdasarkan kategori
     */
    public function byCategory($id)
    {
        $kategori = Category::findOrFail($id);
        $books = $kategori->books()->with('kategori')->latest()->paginate(12);

        return view('books.index', [
            'books' => $books,
            'categories' => Category::all(),
            'selectedCategory' => $id
        ]);
    }

    /**
     * Form tambah buku
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Simpan buku baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload gambar ke storage dan simpan path ke kolom 'cover'
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['cover'] = $path;  // 🔥 SIMPAN KE KOLOM 'cover'
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Form edit buku
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update data buku
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['cover'] = $path;  // 🔥 SIMPAN KE KOLOM 'cover'
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku
     */
    public function destroy(Book $book)
    {
        // Hapus file gambar jika ada
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}