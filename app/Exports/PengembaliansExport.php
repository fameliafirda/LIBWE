<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengembaliansExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pengembalian::with('pinjaman')->get()->map(function ($item) {
            return [
                'Nama' => $item->nama ?? $item->pinjaman->nama ?? '-',
                'Kelas' => $item->kelas ?? $item->pinjaman->kelas ?? '-',
                'Judul Buku' => $item->judul_buku ?? $item->pinjaman->judul_buku ?? '-',
                'Tanggal Pinjam' => $item->pinjaman->tanggal_pinjam ?? '-',
                'Tanggal Harus Kembali' => $item->tanggal_kembali ?? '-',
                'Tanggal Pengembalian' => $item->tanggal_pengembalian ?? '-',
                'Keterlambatan' => $item->keterlambatan ?? 0,
                'Denda' => $item->denda ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama', 
            'Kelas', 
            'Judul Buku', 
            'Tanggal Pinjam', 
            'Tanggal Harus Kembali',
            'Tanggal Pengembalian',
            'Keterlambatan (hari)', 
            'Denda'
        ];
    }
}