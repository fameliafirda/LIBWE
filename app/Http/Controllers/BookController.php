<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Tampilkan semua buku dengan fitur pencarian dan filter
    public function index(Request $request)
    {
        $query = Book::with('kategori')->latest();

        // Fitur pencarian
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

        return view('books.index', [
            'books' => $books,
            'categories' => $categories,
            'searchQuery' => $request->search,
            'selectedCategory' => $request->kategori
        ]);
    }

    // Tampilkan buku berdasarkan kategori
    public function byCategory($id)
    {
        $kategori = Category::findOrFail($id);
        $books = $kategori->books()
                         ->with('kategori')
                         ->latest()
                         ->paginate(12);

        return view('books.index', [
            'books' => $books,
            'categories' => Category::all(),
            'filterKategori' => $kategori->nama,
            'selectedCategory' => $id
        ]);
    }

    // Form tambah buku
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    // Simpan buku baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer',
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0', // TAMBAHKAN VALIDASI STOK
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['gambar'] = $path;
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Form edit buku
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    // Update data buku
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer',
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0', // TAMBAHKAN VALIDASI STOK
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada
            if ($book->gambar && Storage::disk('public')->exists($book->gambar)) {
                Storage::disk('public')->delete($book->gambar);
            }

            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['gambar'] = $path;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // Hapus buku
    public function destroy(Book $book)
    {
        if ($book->gambar && Storage::disk('public')->exists($book->gambar)) {
            Storage::disk('public')->delete($book->gambar);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}