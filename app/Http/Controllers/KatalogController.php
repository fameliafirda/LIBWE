<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{
    /**
     * Tampilkan daftar buku dengan rekomendasi
     */
    public function index(Request $request)
    {
        // Ambil semua kategori
        $kategoris = Category::all();

        // ==================== REKOMENDASI BUKU POPULER ====================
        $popularBooks = $this->getPopularBooks(10); // Ambil 10 buku paling populer

        // ==================== QUERY KATALOG BUKU ====================
        $query = Book::with('kategori');

        // Pencarian berdasarkan judul, penulis, atau penerbit
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', '%'.$search.'%')
                  ->orWhere('penulis', 'LIKE', '%'.$search.'%')
                  ->orWhere('penerbit', 'LIKE', '%'.$search.'%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Subquery jumlah dipinjam per buku
        $pinjamanSubquery = DB::table('pinjamans')
            ->select('buku_id', DB::raw('COUNT(*) as total_dipinjam'))
            ->groupBy('buku_id');

        // Gabungkan subquery ke query buku
        $books = $query->leftJoinSub($pinjamanSubquery, 'pinjaman_count', function ($join) {
            $join->on('books.id', '=', 'pinjaman_count.buku_id');
        })
        ->select(
            'books.*',
            DB::raw('COALESCE(pinjaman_count.total_dipinjam, 0) as total_dipinjam')
        )
        ->orderByDesc('books.created_at') // Urutkan berdasarkan waktu ditambahkan
        ->paginate(24)
        ->withQueryString();

        return view('katalog.index', [
            'books' => $books,
            'kategoris' => $kategoris,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Mengambil buku yang paling populer berdasarkan histori peminjaman.
     * Diurutkan berdasarkan yang paling banyak dipinjam
     *
     * @param int $limit Jumlah buku yang ditampilkan (default 10)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPopularBooks($limit = 10)
    {
        $cacheKey = 'popular_books_real_only_' . $limit;

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($limit) {
            if (!$this->hasPinjamanTable()) {
                Log::info('Tabel pinjamans tidak ditemukan, rekomendasi dikosongkan.');
                return collect(); // Return empty collection if no table found
            }

            return Book::with('kategori')
                ->join('pinjamans', 'books.id', '=', 'pinjamans.buku_id')
                ->select(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.cover',
                    'books.stok',
                    'books.kategori_id',
                    DB::raw('COUNT(pinjamans.id) as total_dipinjam')
                )
                ->groupBy(
                    'books.id',
                    'books.judul',
                    'books.penulis',
                    'books.penerbit',
                    'books.tahun_terbit',
                    'books.cover',
                    'books.stok',
                    'books.kategori_id'
                )
                ->havingRaw('COUNT(pinjamans.id) > 0') // Buku yang dipinjam
                ->orderByDesc('total_dipinjam')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cek apakah tabel pinjamans ada
     */
    private function hasPinjamanTable()
    {
        try {
            return DB::connection()->getSchemaBuilder()->hasTable('pinjamans');
        } catch (\Exception $e) {
            Log::error('Error checking pinjamans table: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * AJAX Filter
     */
    public function filter(Request $request)
    {
        try {
            $pinjamanSubquery = DB::table('pinjamans')
                ->select('buku_id', DB::raw('COUNT(*) as total_dipinjam'))
                ->groupBy('buku_id');

            $query = Book::with('kategori')
                ->leftJoinSub($pinjamanSubquery, 'pinjaman_count', function ($join) {
                    $join->on('books.id', '=', 'pinjaman_count.buku_id');
                })
                ->select(
                    'books.*',
                    DB::raw('COALESCE(pinjaman_count.total_dipinjam, 0) as total_dipinjam')
                );

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('books.judul', 'LIKE', "%{$search}%")
                      ->orWhere('books.penulis', 'LIKE', "%{$search}%")
                      ->orWhere('books.penerbit', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('kategori')) {
                $query->where('books.kategori_id', $request->kategori);
            }

            $books = $query->orderByDesc('books.created_at')->get();

            return response()->json([
                'success' => true,
                'books'   => $books
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus cache rekomendasi
     */
    public function clearRecommendationCache()
    {
        Cache::forget('popular_books_real_only_10');
        Cache::forget('popular_books_real_only_5');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cache rekomendasi berhasil dihapus'
            ]);
        }

        return back()->with('success', 'Cache rekomendasi berhasil dihapus');
    }
}