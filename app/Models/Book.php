<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'cover',
        'gambar',
        'stok',
        'kategori_id',
        'rak_id', // Tambahkan rak_id
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    /**
     * Get the category that owns the book.
     */
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Alias for kategori() for consistency
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Get the rak that owns the book.
     */
    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

    /**
     * Get the pinjaman records for the book.
     */
    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id');
    }

    /**
     * Get completed pinjaman (yang sudah dikembalikan)
     */
    public function pinjamanSelesai()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id')->where('status', 'sudah dikembalikan');
    }

    /**
     * Get active pinjaman (yang masih dipinjam)
     */
    public function pinjamanAktif()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id')->where('status', 'belum dikembalikan');
    }

    /**
     * Accessor: Get total times this book has been borrowed (selesai)
     */
    public function getTotalDipinjamAttribute()
    {
        return $this->pinjamanSelesai()->count();
    }

    /**
     * Accessor: Get currently borrowed count
     */
    public function getSedangDipinjamAttribute()
    {
        return $this->pinjamanAktif()->count();
    }

    /**
     * Accessor: Get available stock (physical stock - currently borrowed)
     */
    public function getStokTersediaAttribute()
    {
        return max(0, $this->stok - $this->sedang_dipinjam);
    }

    /**
     * Check if stock is available
     */
    public function stokTersedia()
    {
        return $this->stok_tersedia > 0;
    }

    /**
     * Check if book is popular (dipinjam > 10 kali)
     */
    public function getIsPopularAttribute()
    {
        return $this->total_dipinjam > 10;
    }

    /**
     * Get popularity level
     */
    public function getPopularityLevelAttribute()
    {
        $total = $this->total_dipinjam;
        if ($total >= 50) return 'very_hot';
        if ($total >= 25) return 'hot';
        if ($total >= 10) return 'popular';
        if ($total >= 5) return 'trending';
        return 'normal';
    }

    /**
     * Decrease stock when book is borrowed
     */
    public function kurangiStok()
    {
        if ($this->stok > 0) {
            $this->decrement('stok');
            self::clearPopularBooksCache();
            return true;
        }
        return false;
    }

    /**
     * Increase stock when book is returned
     */
    public function tambahStok()
    {
        $this->increment('stok');
        self::clearPopularBooksCache();
    }

    /**
     * Get book location (rak nomor)
     */
    public function getLokasiAttribute()
    {
        return $this->rak ? "Rak {$this->rak->nomor}" : 'Belum ditempatkan';
    }

    /**
     * Get full location info
     */
    public function getFullLocationAttribute()
    {
        if ($this->rak && $this->category) {
            return "{$this->rak->judul} (Rak {$this->rak->nomor}) - Kategori: {$this->category->nama}";
        } elseif ($this->rak) {
            return "{$this->rak->judul} (Rak {$this->rak->nomor})";
        }
        return 'Belum ditempatkan';
    }

    /**
     * Scope: Get most popular books based on borrowing history
     */
    public function scopeMostPopular($query, $limit = 10)
    {
        return $query->withCount(['pinjamanSelesai as total_dipinjam'])
                     ->orderBy('total_dipinjam', 'desc')
                     ->limit($limit);
    }

    /**
     * Scope: Get trending books (popular in last 30 days)
     */
    public function scopeTrending($query, $limit = 10)
    {
        return $query->withCount(['pinjamanSelesai as total_dipinjam' => function($q) {
                        $q->where('tanggal_kembali', '>=', now()->subDays(30));
                    }])
                     ->orderBy('total_dipinjam', 'desc')
                     ->limit($limit);
    }

    /**
     * Scope: Get books with stock available
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope: Get books by rak
     */
    public function scopeByRak($query, $rakId)
    {
        if ($rakId) {
            return $query->where('rak_id', $rakId);
        }
        return $query;
    }

    /**
     * Scope: Search by title or author
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                         ->orWhere('penulis', 'like', '%' . $search . '%')
                         ->orWhere('penerbit', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('kategori_id', $categoryId);
        }
        return $query;
    }

    /**
     * Static method: Get cached popular books
     */
    public static function getPopularBooks($limit = 10, $cacheMinutes = 30)
    {
        $cacheKey = 'popular_books_' . $limit;
        
        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function() use ($limit) {
            return self::with(['kategori', 'rak'])
                ->withCount(['pinjamanSelesai as total_dipinjam'])
                ->orderBy('total_dipinjam', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Static method: Clear popular books cache
     */
    public static function clearPopularBooksCache()
    {
        Cache::forget('popular_books_10');
        Cache::forget('popular_books_6');
        Cache::forget('popular_books_8');
        Cache::forget('popular_books_limit_10');
        Cache::forget('popular_books_limit_6');
        Cache::forget('popular_books_limit_8');
        Cache::forget('popular_books_by_title_10');
        Cache::forget('popular_books_by_title_6');
        Cache::forget('popular_books_by_title_8');
        
        $periods = ['week', 'month', 'year', 'all'];
        foreach ($periods as $period) {
            Cache::forget('popular_books_' . $period . '_10');
            Cache::forget('popular_books_' . $period . '_6');
            Cache::forget('popular_books_' . $period . '_8');
        }
    }
}