<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Book::select('judul', 'penulis', 'penerbit', 'tahun_terbit')->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Penulis', 'Penerbit', 'Tahun Terbit'];
    }
}
