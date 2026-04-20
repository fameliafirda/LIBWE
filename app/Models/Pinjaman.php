<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjamans';

    protected $fillable = [
        'nama',
        'kelas',
        'jenis_kelamin',
        'judul_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'denda',
        'anggota_id',
        'buku_id'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'denda' => 'integer',
    ];

    /**
     * Relasi ke model Anggota
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Relasi ke model Book (via buku_id)
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    /**
     * Alias untuk relasi book
     */
    public function buku()
    {
        return $this->book();
    }

    /**
     * Relasi ke tabel pengembalian
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'pinjaman_id');
    }

    /**
     * Accessor untuk judul_buku
     */
    public function getJudulBukuAttribute($value)
    {
        if ($this->buku) {
            return $this->buku->judul;
        }
        return $value;
    }

    /**
     * Accessor untuk nama peminjam
     */
    public function getNamaAttribute($value)
    {
        if ($this->anggota) {
            return $this->anggota->nama;
        }
        return $value;
    }

    /**
     * Accessor untuk kelas
     */
    public function getKelasAttribute($value)
    {
        if ($this->anggota) {
            return $this->anggota->kelas;
        }
        return $value;
    }

    /**
     * Accessor untuk status dalam bentuk teks
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'sudah dikembalikan' ? 'Sudah Dikembalikan' : 'Belum Dikembalikan';
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'sudah dikembalikan') {
            return '<span class="badge bg-success">Sudah Dikembalikan</span>';
        }
        return '<span class="badge bg-warning">Belum Dikembalikan</span>';
    }

    /**
     * Accessor untuk denda yang diformat
     */
    public function getDendaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    /**
     * Accessor untuk mendapatkan tanggal pinjam dalam format Indonesia
     */
    public function getTanggalPinjamFormattedAttribute()
    {
        return $this->tanggal_pinjam ? $this->tanggal_pinjam->format('d/m/Y') : '-';
    }

    /**
     * Accessor untuk mendapatkan tanggal kembali dalam format Indonesia
     */
    public function getTanggalKembaliFormattedAttribute()
    {
        return $this->tanggal_kembali ? $this->tanggal_kembali->format('d/m/Y') : '-';
    }

    /**
     * Accessor untuk mendapatkan lama peminjaman dalam hari
     */
    public function getLamaPinjamAttribute()
    {
        if ($this->tanggal_pinjam && $this->tanggal_kembali) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali);
        }
        return null;
    }

    /**
     * Accessor untuk cek apakah peminjaman terlambat
     */
    public function getIsTerlambatAttribute()
    {
        if ($this->status === 'belum dikembalikan' && $this->tanggal_pinjam) {
            $jatuhTempo = $this->tanggal_pinjam->copy()->addDays(7);
            return now()->gt($jatuhTempo);
        }
        return false;
    }

    /**
     * Scope untuk peminjaman yang sudah dikembalikan
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'sudah dikembalikan');
    }

    /**
     * Scope untuk peminjaman yang belum dikembalikan
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'belum dikembalikan');
    }

    /**
     * Scope untuk peminjaman yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'belum dikembalikan')
                     ->whereRaw('DATE_ADD(tanggal_pinjam, INTERVAL 7 DAY) < NOW()');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                         ->orWhere('judul_buku', 'like', '%' . $search . '%')
                         ->orWhere('kelas', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByMonth($query, $year, $month)
    {
        if ($year && $month) {
            return $query->whereYear('tanggal_pinjam', $year)
                         ->whereMonth('tanggal_pinjam', $month);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        if ($year) {
            return $query->whereYear('tanggal_pinjam', $year);
        }
        return $query;
    }

    /**
     * Hitung denda untuk peminjaman yang belum dikembalikan
     */
    public function hitungDenda($dendaPerHari = 500)
    {
        if ($this->status === 'sudah dikembalikan') {
            return $this->denda;
        }

        $jatuhTempo = $this->tanggal_pinjam->copy()->addDays(7);
        $hariTerlambat = max(0, now()->diffInDays($jatuhTempo, false));
        
        if ($hariTerlambat <= 0) {
            return 0;
        }
        
        return $hariTerlambat * $dendaPerHari;
    }

    /**
     * Proses pengembalian buku
     */
    public function prosesPengembalian($tanggalPengembalian = null)
    {
        $tanggalPengembalian = $tanggalPengembalian ?? now();
        $jatuhTempo = $this->tanggal_pinjam->copy()->addDays(7);
        
        $hariTerlambat = max(0, $tanggalPengembalian->diffInDays($jatuhTempo, false));
        $denda = $hariTerlambat * 500;
        
        $this->update([
            'tanggal_kembali' => $tanggalPengembalian,
            'status' => 'sudah dikembalikan',
            'denda' => $denda,
        ]);
        
        // Update atau buat record pengembalian
        Pengembalian::updateOrCreate(
            ['pinjaman_id' => $this->id],
            [
                'nama' => $this->nama,
                'kelas' => $this->kelas,
                'judul_buku' => $this->judul_buku,
                'tanggal_kembali' => $jatuhTempo->toDateString(),
                'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                'keterlambatan' => $hariTerlambat,
                'denda' => $denda,
            ]
        );
        
        // Tambah stok buku
        if ($this->buku) {
            $this->buku->increment('stok');
        }
        
        return $denda;
    }

    /**
     * Get statistik peminjaman per bulan
     */
    public static function getMonthlyStats($year = null)
    {
        $year = $year ?? date('Y');
        
        return self::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Get top 5 buku yang paling sering dipinjam
     */
    public static function getTopBooks($limit = 5)
    {
        return self::selectRaw('judul_buku, COUNT(*) as total_dipinjam')
            ->where('status', 'sudah dikembalikan')
            ->groupBy('judul_buku')
            ->orderBy('total_dipinjam', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top 5 peminjam terbanyak
     */
    public static function getTopPeminjam($limit = 5)
    {
        return self::selectRaw('nama, kelas, COUNT(*) as total_pinjam')
            ->groupBy('nama', 'kelas')
            ->orderBy('total_pinjam', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get total denda keseluruhan
     */
    public static function getTotalDenda()
    {
        return self::sum('denda');
    }
}