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
        'gambar', 
        'stok',
        'kategori_id',
        'rak_id',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function kategori()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id');
    }

    public function pinjamanSelesai()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id')
                    ->where('status', 'sudah dikembalikan');
    }

    public function pinjamanAktif()
    {
        return $this->hasMany(Pinjaman::class, 'buku_id')
                    ->where('status', 'belum dikembalikan');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getTotalDipinjamAttribute()
    {
        return $this->pinjamanSelesai()->count();
    }

    public function getSedangDipinjamAttribute()
    {
        return $this->pinjamanAktif()->count();
    }

    public function getStokTersediaAttribute()
    {
        return max(0, $this->stok - $this->sedang_dipinjam);
    }

    public function getIsPopularAttribute()
    {
        return $this->total_dipinjam > 10;
    }

    public function getPopularityLevelAttribute()
    {
        $total = $this->total_dipinjam;

        if ($total >= 50) return 'very_hot';
        if ($total >= 25) return 'hot';
        if ($total >= 10) return 'popular';
        if ($total >= 5) return 'trending';

        return 'normal';
    }

    // 🔥 FIX: Mengarah langsung ke folder public (tanpa lewat storage/symlink)
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset($this->gambar);
        }
        return asset('images/no-image.png');
    }

    public function getLokasiAttribute()
    {
        return $this->rak ? "Rak {$this->rak->nomor}" : 'Belum ditempatkan';
    }

    public function getFullLocationAttribute()
    {
        if ($this->rak && $this->category) {
            return "{$this->rak->judul} (Rak {$this->rak->nomor}) - Kategori: {$this->category->nama}";
        } elseif ($this->rak) {
            return "{$this->rak->judul} (Rak {$this->rak->nomor})";
        }

        return 'Belum ditempatkan';
    }

    /*
    |--------------------------------------------------------------------------
    | METHOD
    |--------------------------------------------------------------------------
    */

    public function stokTersedia()
    {
        return $this->stok_tersedia > 0;
    }

    public function kurangiStok()
    {
        if ($this->stok > 0) {
            $this->decrement('stok');
            self::clearPopularBooksCache();
            return true;
        }
        return false;
    }

    public function tambahStok()
    {
        $this->increment('stok');
        self::clearPopularBooksCache();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeMostPopular($query, $limit = 10)
    {
        return $query->withCount(['pinjamanSelesai as total_dipinjam'])
                     ->orderBy('total_dipinjam', 'desc')
                     ->limit($limit);
    }

    public function scopeTrending($query, $limit = 10)
    {
        return $query->withCount(['pinjamanSelesai as total_dipinjam' => function($q) {
                        $q->where('tanggal_kembali', '>=', now()->subDays(30));
                    }])
                     ->orderBy('total_dipinjam', 'desc')
                     ->limit($limit);
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    public function scopeByRak($query, $rakId)
    {
        return $rakId ? $query->where('rak_id', $rakId) : $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                         ->orWhere('penulis', 'like', '%' . $search . '%')
                         ->orWhere('penerbit', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('kategori_id', $categoryId) : $query;
    }

    /*
    |--------------------------------------------------------------------------
    | CACHE
    |--------------------------------------------------------------------------
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

    public static function clearPopularBooksCache()
    {
        Cache::flush();
    }
}