<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kelas', 'jenis_kelamin'];

    // Relasi dengan pinjaman
    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class, 'anggota_id');
    }

    // Method untuk mendapatkan total denda
    public function totalDenda()
    {
        return $this->pinjamans ? $this->pinjamans->sum('denda') : 0;
    }

    // Method untuk mendapatkan daftar judul buku yang pernah dipinjam
    public function bukuYangDipinjam()
    {
        return $this->pinjamans
            ->pluck('judul_buku')
            ->filter();
    }
}
