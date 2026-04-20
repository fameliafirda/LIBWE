<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'pinjaman_id',
        'nama',
        'kelas',
        'judul_buku',
        'tanggal_kembali',
        'tanggal_pengembalian',
        'keterlambatan',
        'denda',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'tanggal_pengembalian' => 'date',
        'keterlambatan' => 'integer',
        'denda' => 'integer',
    ];

    /**
     * Relasi: pengembalian milik satu peminjaman
     */
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /**
     * Accessor untuk denda yang diformat menjadi Rupiah
     */
    public function getDendaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    /**
     * Accessor untuk status keterlambatan (dengan HTML badge)
     */
    public function getStatusKeterlambatanAttribute()
    {
        if ($this->keterlambatan <= 0) {
            return '<span class="badge bg-success">✓ Tepat Waktu</span>';
        }
        return '<span class="badge bg-danger">⚠️ Terlambat ' . $this->keterlambatan . ' hari</span>';
    }

    /**
     * Accessor untuk status keterlambatan (teks biasa)
     */
    public function getStatusKeterlambatanTextAttribute()
    {
        if ($this->keterlambatan <= 0) {
            return 'Tepat Waktu';
        }
        return 'Terlambat ' . $this->keterlambatan . ' hari';
    }

    /**
     * Accessor untuk mendapatkan tanggal kembali dalam format Indonesia
     */
    public function getTanggalKembaliFormattedAttribute()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_kembali->format('d/m/Y');
        }
        return '-';
    }

    /**
     * Accessor untuk mendapatkan tanggal pengembalian dalam format Indonesia
     */
    public function getTanggalPengembalianFormattedAttribute()
    {
        if ($this->tanggal_pengembalian) {
            return $this->tanggal_pengembalian->format('d/m/Y');
        }
        return '-';
    }

    /**
     * Accessor untuk mendapatkan informasi lengkap pengembalian
     */
    public function getInfoPengembalianAttribute()
    {
        return [
            'nama' => $this->nama,
            'kelas' => $this->kelas,
            'judul_buku' => $this->judul_buku,
            'tanggal_kembali' => $this->tanggal_kembali_formatted,
            'tanggal_pengembalian' => $this->tanggal_pengembalian_formatted,
            'keterlambatan' => $this->keterlambatan,
            'denda' => $this->denda_formatted,
            'status' => $this->status_keterlambatan_text,
        ];
    }

    /**
     * Scope untuk filter berdasarkan nama
     */
    public function scopeByNama($query, $nama)
    {
        if ($nama) {
            return $query->where('nama', 'like', '%' . $nama . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kelas
     */
    public function scopeByKelas($query, $kelas)
    {
        if ($kelas) {
            return $query->where('kelas', 'like', '%' . $kelas . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan judul buku
     */
    public function scopeByJudulBuku($query, $judul)
    {
        if ($judul) {
            return $query->where('judul_buku', 'like', '%' . $judul . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('keterlambatan', '>', 0);
    }

    /**
     * Scope untuk filter yang tepat waktu
     */
    public function scopeTepatWaktu($query)
    {
        return $query->where('keterlambatan', '=', 0);
    }

    /**
     * Scope untuk filter berdasarkan range tanggal pengembalian
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_pengembalian', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Hitung total denda dari semua pengembalian
     */
    public static function getTotalDenda()
    {
        return self::sum('denda');
    }

    /**
     * Hitung rata-rata keterlambatan
     */
    public static function getRataRataKeterlambatan()
    {
        return self::avg('keterlambatan');
    }

    /**
     * Get statistik pengembalian per bulan
     */
    public static function getMonthlyStats($year = null)
    {
        $year = $year ?? date('Y');
        
        return self::selectRaw('MONTH(tanggal_pengembalian) as month, COUNT(*) as total, SUM(denda) as total_denda')
            ->whereYear('tanggal_pengembalian', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get top 5 buku yang paling sering terlambat dikembalikan
     */
    public static function getTopBukuTerlambat($limit = 5)
    {
        return self::selectRaw('judul_buku, COUNT(*) as total_terlambat, AVG(keterlambatan) as rata_rata_keterlambatan')
            ->where('keterlambatan', '>', 0)
            ->groupBy('judul_buku')
            ->orderBy('total_terlambat', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get statistik berdasarkan kelas
     */
    public static function getStatsByKelas()
    {
        return self::selectRaw('kelas, COUNT(*) as total_pengembalian, SUM(denda) as total_denda, AVG(keterlambatan) as rata_rata_keterlambatan')
            ->groupBy('kelas')
            ->orderBy('total_pengembalian', 'desc')
            ->get();
    }
}