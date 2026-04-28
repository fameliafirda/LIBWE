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
    | ACCESSOR - 🔥 BAGIAN PALING PENTING
    |--------------------------------------------------------------------------
    */

    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            // Kita langsung panggil folder public, tanpa kata 'storage/'
            return asset($this->gambar);
        }

        return asset('images/no-image.png');
    }

    /*
    |--------------------------------------------------------------------------
    | SISANYA TETAP SAMA
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

    public function stokTersedia() { return $this->stok_tersedia > 0; }

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

    public function scopeMostPopular($query, $limit = 10)
    {
        return $query->withCount(['pinjamanSelesai as total_dipinjam'])->orderBy('total_dipinjam', 'desc')->limit($limit);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('judul', 'like', '%' . $search . '%')->orWhere('penulis', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public static function clearPopularBooksCache() { Cache::flush(); }
}