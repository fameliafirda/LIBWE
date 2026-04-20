<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = ['nama'];

    /**
     * Relasi one-to-many ke Book
     * Satu kategori bisa memiliki banyak buku
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'kategori_id');
    }

    /**
     * Relasi many-to-many dengan Rak melalui tabel rak_kategoris
     */
    public function raks()
    {
        return $this->belongsToMany(Rak::class, 'rak_kategoris', 'kategori_id', 'rak_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke RakKategori (pivot table)
     */
    public function rakKategoris()
    {
        return $this->hasMany(RakKategori::class, 'kategori_id');
    }

    /**
     * Get books count in specific rak
     */
    public function getBooksCountInRak($rakId)
    {
        return $this->books()->where('rak_id', $rakId)->count();
    }

    /**
     * Get all books in specific rak
     */
    public function getBooksInRak($rakId)
    {
        return $this->books()->where('rak_id', $rakId)->get();
    }

    /**
     * Cek apakah kategori ini sudah terhubung dengan rak tertentu
     */
    public function isInRak($rakId)
    {
        return $this->raks()->where('rak_id', $rakId)->exists();
    }

    /**
     * Dapatkan semua rak yang berisi kategori ini
     */
    public function getRakListAttribute()
    {
        return $this->raks->pluck('judul')->implode(', ');
    }

    /**
     * Get formatted rak list for display
     */
    public function getRakListFormattedAttribute()
    {
        $rakList = $this->raks->pluck('display_name')->toArray();
        return !empty($rakList) ? implode(', ', $rakList) : 'Belum ada rak';
    }

    /**
     * Scope: Search categories by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope: Get categories with books count
     */
    public function scopeWithBooksCount($query)
    {
        return $query->withCount('books');
    }

    /**
     * Check if category has any books
     */
    public function hasBooks()
    {
        return $this->books()->count() > 0;
    }

    /**
     * Check if category can be deleted (no books associated)
     */
    public function canBeDeleted()
    {
        return !$this->hasBooks();
    }
}