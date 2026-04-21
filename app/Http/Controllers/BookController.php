<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // 🔥 PROSES UPLOAD GAMBAR
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Buat nama file unik: timestamp_nama_original.jpg
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Simpan ke folder storage/app/public/gambar_buku/
            $path = $file->storeAs('gambar_buku', $filename, 'public');
            
            // Simpan path ke database (kolom 'cover')
            $validated['cover'] = $path;
            
            // Debug: catat log jika berhasil
            Log::info('Gambar berhasil diupload: ' . $path);
        } else {
            Log::info('Tidak ada file gambar yang diupload');
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

        // 🔥 PROSES UPLOAD GAMBAR BARU
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
                Log::info('Gambar lama dihapus: ' . $book->cover);
            }

            $file = $request->file('gambar');
            
            // Buat nama file unik
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Simpan ke folder storage/app/public/gambar_buku/
            $path = $file->storeAs('gambar_buku', $filename, 'public');
            
            // Simpan path ke database (kolom 'cover')
            $validated['cover'] = $path;
            
            Log::info('Gambar baru diupload: ' . $path);
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
        // Hapus file gambar jika ada
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
            Log::info('Gambar dihapus: ' . $book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}