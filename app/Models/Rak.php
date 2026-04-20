<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rak extends Model
{
    use HasFactory;

    protected $table = 'raks';
    
    protected $fillable = [
        'judul',
        'nomor',
        'deskripsi',
        'warna'
    ];

    protected $casts = [
        'warna' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'total_buku',
        'total_kategori',
        'display_name'
    ];

    /**
     * Relasi ke RakKategori (pivot table)
     */
    public function rakKategoris(): HasMany
    {
        return $this->hasMany(RakKategori::class, 'rak_id');
    }

    /**
     * Relasi many-to-many dengan Category melalui tabel rak_kategoris
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'rak_kategoris', 'rak_id', 'kategori_id')
                    ->withTimestamps()
                    ->withPivot('id');
    }

    /**
     * Relasi ke Book (buku yang langsung ditetapkan ke rak ini)
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'rak_id');
    }

    /**
     * Mendapatkan semua buku yang berada di rak ini (termasuk dari kategori yang terhubung)
     */
    public function getAllBooksAttribute()
    {
        $kategoriIds = $this->rakKategoris->pluck('kategori_id')->toArray();
        
        if (empty($kategoriIds)) {
            return collect();
        }
        
        // Ambil buku yang kategorinya ada di rak ini
        return Book::whereIn('kategori_id', $kategoriIds)
                   ->with(['category', 'rak'])
                   ->get();
    }

    /**
     * Menghitung total buku di rak ini
     */
    public function getTotalBukuAttribute(): int
    {
        $kategoriIds = $this->rakKategoris->pluck('kategori_id')->toArray();
        
        if (empty($kategoriIds)) {
            return 0;
        }
        
        return Book::whereIn('kategori_id', $kategoriIds)->count();
    }

    /**
     * Menghitung total buku per kategori (dengan cache)
     */
    public function getBooksPerCategoryAttribute(): array
    {
        $kategoriIds = $this->rakKategoris->pluck('kategori_id')->toArray();
        
        if (empty($kategoriIds)) {
            return [];
        }
        
        $booksPerCategory = [];
        foreach ($this->categories as $category) {
            $booksPerCategory[$category->id] = [
                'nama' => $category->nama,
                'jumlah' => Book::where('kategori_id', $category->id)->count()
            ];
        }
        
        return $booksPerCategory;
    }

    /**
     * Menghitung total kategori di rak ini
     */
    public function getTotalKategoriAttribute(): int
    {
        return $this->categories()->count();
    }

    /**
     * Mengecek apakah rak memiliki kategori tertentu
     */
    public function hasCategory(int $categoryId): bool
    {
        return $this->categories()->where('kategori_id', $categoryId)->exists();
    }

    /**
     * Menambahkan kategori ke rak
     */
    public function addCategory(int $categoryId): bool
    {
        if (!$this->hasCategory($categoryId)) {
            $this->categories()->attach($categoryId);
            return true;
        }
        return false;
    }

    /**
     * Menambahkan multiple kategori sekaligus
     */
    public function addMultipleCategories(array $categoryIds): array
    {
        $added = [];
        $failed = [];
        
        foreach ($categoryIds as $categoryId) {
            if ($this->addCategory($categoryId)) {
                $added[] = $categoryId;
            } else {
                $failed[] = $categoryId;
            }
        }
        
        return ['added' => $added, 'failed' => $failed];
    }

    /**
     * Menghapus kategori dari rak
     */
    public function removeCategory(int $categoryId): void
    {
        $this->categories()->detach($categoryId);
    }

    /**
     * Menghapus semua kategori dari rak
     */
    public function removeAllCategories(): void
    {
        $this->categories()->detach();
    }

    /**
     * Mendapatkan buku dengan filter
     */
    public function getBooksWithFilter(?string $search = null, ?int $categoryId = null)
    {
        $kategoriIds = $this->rakKategoris->pluck('kategori_id')->toArray();
        
        if (empty($kategoriIds)) {
            return collect();
        }
        
        $query = Book::whereIn('kategori_id', $kategoriIds)
                     ->with(['category', 'rak']);
        
        if ($categoryId && $categoryId !== 'all') {
            $query->where('kategori_id', $categoryId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('penulis', 'LIKE', "%{$search}%")
                  ->orWhere('penerbit', 'LIKE', "%{$search}%");
            });
        }
        
        return $query->get();
    }

    /**
     * Mengecek apakah rak kosong (tidak ada kategori dan tidak ada buku langsung)
     */
    public function isEmpty(): bool
    {
        return $this->categories()->count() === 0 && $this->books()->count() === 0;
    }

    /**
     * Mendapatkan statistik lengkap rak
     */
    public function getStatisticsAttribute(): array
    {
        $kategoriIds = $this->rakKategoris->pluck('kategori_id')->toArray();
        
        return [
            'total_kategori' => $this->categories()->count(),
            'total_buku' => Book::whereIn('kategori_id', $kategoriIds)->count(),
            'total_buku_langsung' => $this->books()->count(),
            'total_kategori_terhubung' => $this->rakKategoris()->count(),
        ];
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('nomor', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeHasCategory($query, int $categoryId)
    {
        return $query->whereHas('categories', function($q) use ($categoryId) {
            $q->where('kategori_id', $categoryId);
        });
    }

    /**
     * Scope untuk rak yang memiliki buku
     */
    public function scopeHasBooks($query)
    {
        return $query->whereHas('rakKategoris', function($q) {
            $q->whereExists(function($sub) {
                $sub->select('id')
                    ->from('books')
                    ->whereColumn('books.kategori_id', 'rak_kategoris.kategori_id');
            });
        });
    }

    /**
     * Accessor untuk display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "Rak {$this->nomor} - {$this->judul}";
    }

    /**
     * Accessor untuk mendapatkan warna dengan format hex
     */
    public function getWarnaHexAttribute(): string
    {
        $warna = $this->warna ?? '#ffffff';
        if (!str_starts_with($warna, '#')) {
            $warna = '#' . $warna;
        }
        return $warna;
    }

    /**
     * Boot method untuk event model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Event saat menghapus rak
        static::deleting(function($rak) {
            // Hapus semua relasi rak_kategoris
            $rak->rakKategoris()->delete();
            
            // Update books yang memiliki rak_id ini
            Book::where('rak_id', $rak->id)->update(['rak_id' => null]);
        });
    }
}