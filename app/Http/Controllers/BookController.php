<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        // Validasi input
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 🔥 FIX: Simpan langsung ke folder public/gambar_buku
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Pindahkan file ke public/gambar_buku
            $file->move(public_path('gambar_buku'), $filename);
            
            // Simpan path relatif ke database
            $validated['gambar'] = 'gambar_buku/' . $filename;
            
            Log::info('Gambar berhasil diupload ke public: ' . $validated['gambar']);
        }

        // Simpan ke database
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
        // Validasi input
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 🔥 FIX: Update langsung ke folder public/gambar_buku
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada di folder public
            if ($book->gambar && file_exists(public_path($book->gambar))) {
                @unlink(public_path($book->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Pindahkan ke public/gambar_buku
            $file->move(public_path('gambar_buku'), $filename);
            
            $validated['gambar'] = 'gambar_buku/' . $filename;
        }

        // Update database
        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku
     */
    public function destroy(Book $book)
    {
        // Hapus file gambar dari folder public jika ada
        if ($book->gambar && file_exists(public_path($book->gambar))) {
            @unlink(public_path($book->gambar));
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}