<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rak;
use App\Models\Category;
use App\Models\RakKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RakController extends Controller
{
    public function index(Request $request)
    {
        $raks = Rak::with('categories')->get();
        
        // Hitung jumlah buku per rak
        foreach ($raks as $rak) {
            $kategoriIds = RakKategori::where('rak_id', $rak->id)->pluck('kategori_id')->toArray();
            $rak->total_buku = Book::whereIn('kategori_id', $kategoriIds)->count();
        }
        
        $rakId = $request->get('rak_id');
        $selectedRak = null;
        $books = collect();

        if ($rakId) {
            $selectedRak = Rak::with('categories')->find($rakId);
            if ($selectedRak) {
                $kategoriIds = RakKategori::where('rak_id', $rakId)->pluck('kategori_id')->toArray();
                if (!empty($kategoriIds)) {
                    $books = Book::with(['category', 'rak'])
                        ->whereIn('kategori_id', $kategoriIds)
                        ->get();
                }
            }
        }

        return view('rak.index', compact('raks', 'selectedRak', 'books', 'rakId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor' => 'required|string|unique:raks,nomor',
            'deskripsi' => 'nullable|string',
        ]);

        Rak::create($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rak = Rak::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor' => 'required|string|unique:raks,nomor,' . $id,
            'deskripsi' => 'nullable|string',
        ]);
        $rak->update($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak berhasil diupdate!');
    }

    public function destroy($id)
    {
        $rak = Rak::findOrFail($id);
        RakKategori::where('rak_id', $id)->delete();
        $rak->delete();
        return redirect()->route('rak.index')->with('success', 'Rak berhasil dihapus!');
    }

    public function addCategory(Request $request, $rakId)
    {
        $request->validate(['kategori_id' => 'required|exists:categories,id']);
        
        $exists = RakKategori::where('rak_id', $rakId)->where('kategori_id', $request->kategori_id)->exists();
        if ($exists) {
            return redirect()->route('rak.index', ['rak_id' => $rakId])->with('error', 'Kategori sudah ada!');
        }
        
        RakKategori::create(['rak_id' => $rakId, 'kategori_id' => $request->kategori_id]);
        return redirect()->route('rak.index', ['rak_id' => $rakId])->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function removeCategory($rakId, $kategoriId)
    {
        RakKategori::where('rak_id', $rakId)->where('kategori_id', $kategoriId)->delete();
        return redirect()->route('rak.index', ['rak_id' => $rakId])->with('success', 'Kategori berhasil dihapus!');
    }

    // API untuk AJAX - GET BOOKS dengan filter
    public function getBooks(Request $request)
    {
        $rakId = $request->get('rak_id');
        $categoryId = $request->get('category_id');
        $search = $request->get('search', '');
        
        if (!$rakId) {
            return response()->json(['success' => false, 'message' => 'Rak ID diperlukan']);
        }
        
        $rak = Rak::find($rakId);
        if (!$rak) {
            return response()->json(['success' => false, 'message' => 'Rak tidak ditemukan']);
        }
        
        $kategoriIds = RakKategori::where('rak_id', $rakId)->pluck('kategori_id')->toArray();
        
        if (empty($kategoriIds)) {
            return response()->json(['success' => true, 'books' => [], 'count' => 0]);
        }
        
        $query = Book::with(['category', 'rak'])->whereIn('kategori_id', $kategoriIds);
        
        if ($categoryId && $categoryId !== 'all') {
            $query->where('kategori_id', $categoryId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }
        
        $books = $query->get();
        
        $formattedBooks = $books->map(function($book) {
            return [
                'id' => $book->id,
                'judul' => $book->judul,
                'penulis' => $book->penulis ?? 'Penulis tidak diketahui',
                'gambar' => $book->gambar,
                'category_nama' => $book->category ? $book->category->nama : 'Tanpa Kategori',
                'category_id' => $book->kategori_id,
                'rak_nomor' => $book->rak ? $book->rak->nomor : '-',
                'tahun' => $book->tahun_terbit ?? '-',
            ];
        });
        
        return response()->json([
            'success' => true,
            'books' => $formattedBooks,
            'count' => $formattedBooks->count(),
            'rak' => ['id' => $rak->id, 'judul' => $rak->judul, 'nomor' => $rak->nomor]
        ]);
    }
}