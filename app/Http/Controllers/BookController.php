<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Tampilkan semua buku
    public function index(Request $request)
    {
        $query = Book::with('kategori')->latest();

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

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    // Filter kategori
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

    // Form tambah
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    // 🔥 STORE (FIX DI SINI)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer',
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['cover'] = $path; // 🔥 SIMPAN KE COVER
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Form edit
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    // 🔥 UPDATE (FIX DI SINI)
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer',
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {

            // hapus gambar lama
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $path = $request->file('gambar')->store('gambar_buku', 'public');
            $validated['cover'] = $path; // 🔥 SIMPAN KE COVER
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // 🔥 DELETE (FIX DI SINI)
    public function destroy(Book $book)
    {
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}