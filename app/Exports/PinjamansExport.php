<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PinjamansExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pinjaman::select('nama', 'kelas', 'judul_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Kelas', 'Judul Buku', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status'];
    }
}

