<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RakKategori extends Model
{
    use HasFactory;

    protected $table = 'rak_kategoris';
    
    protected $fillable = [
        'rak_id',
        'kategori_id'
    ];

    public $timestamps = true;

    /**
     * Relasi belongsTo ke Rak
     */
    public function rak(): BelongsTo
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

    /**
     * Relasi belongsTo ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Alias for category()
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Accessor untuk mendapatkan nama rak
     */
    public function getRakNameAttribute(): string
    {
        return $this->rak ? $this->rak->judul : 'Rak tidak ditemukan';
    }

    /**
     * Accessor untuk mendapatkan nomor rak
     */
    public function getRakNomorAttribute(): string
    {
        return $this->rak ? $this->rak->nomor : '-';
    }

    /**
     * Accessor untuk mendapatkan nama kategori
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category ? $this->category->nama : 'Kategori tidak ditemukan';
    }

    /**
     * Accessor untuk mendapatkan display name lengkap
     */
    public function getDisplayNameAttribute(): string
    {
        if (!$this->rak || !$this->category) {
            return 'Data tidak lengkap';
        }
        return "{$this->rak->judul} - {$this->category->nama}";
    }

    /**
     * Accessor untuk mendapatkan formatted data (alias dari display_name)
     */
    public function getFormattedAttribute(): string
    {
        return $this->display_name;
    }

    /**
     * Mendapatkan semua buku dari kategori ini yang berada di rak tertentu
     */
    public function getBooksAttribute()
    {
        if (!$this->category) {
            return collect();
        }
        
        return Book::where('kategori_id', $this->kategori_id)
                   ->with(['category', 'rak'])
                   ->get();
    }

    /**
     * Mendapatkan jumlah buku dari kategori ini
     */
    public function getBooksCountAttribute(): int
    {
        if (!$this->category) {
            return 0;
        }
        
        return Book::where('kategori_id', $this->kategori_id)->count();
    }

    /**
     * Scope untuk mencari berdasarkan rak
     */
    public function scopeByRak($query, $rakId)
    {
        return $query->where('rak_id', $rakId);
    }

    /**
     * Scope untuk mencari berdasarkan kategori
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('kategori_id', $categoryId);
    }

    /**
     * Scope untuk eager loading dengan rak dan kategori
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['rak', 'category']);
    }

    /**
     * Cek apakah relasi ini valid (rak dan kategori都存在)
     */
    public function isValid(): bool
    {
        return $this->rak()->exists() && $this->category()->exists();
    }
}