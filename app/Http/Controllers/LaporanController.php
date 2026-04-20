<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Pengembalian;
use App\Models\Book;
use App\Exports\BooksExport;
use App\Exports\PinjamansExport;
use App\Exports\PengembaliansExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // Tampilkan halaman laporan peminjaman
    public function laporanPeminjaman(Request $request)
    {
        $query = Pinjaman::with(['anggota', 'pengembalian'])->latest();

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pinjam', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pinjam', $request->tahun);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->get(); // Menggunakan get() karena kita butuh grouping

        return view('laporan.peminjaman', compact('pinjamans'));
    }

    // Tampilkan halaman laporan buku
    public function laporanBuku(Request $request)
    {
        $query = Book::with('kategori')->latest();

        // Filter berdasarkan bulan (created_at)
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter berdasarkan tahun (created_at)
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $books = $query->paginate(20)->withQueryString();

        return view('laporan.buku', compact('books'));
    }

    // Tampilkan halaman laporan pengembalian
    public function laporanPengembalian(Request $request)
    {
        $query = Pengembalian::with('pinjaman')->latest();

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pengembalian', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pengembalian', $request->tahun);
        }

        $pengembalians = $query->paginate(20)->withQueryString();

        return view('laporan.pengembalian', compact('pengembalians'));
    }

    // Export laporan buku ke Excel
    public function exportBuku()
    {
        return Excel::download(new BooksExport, 'laporan_buku.xlsx');
    }

    // Export laporan peminjaman ke Excel
    public function exportPeminjaman()
    {
        return Excel::download(new PinjamansExport, 'laporan_peminjaman.xlsx');
    }

    // Export laporan pengembalian ke Excel
    public function exportPengembalian()
    {
        return Excel::download(new PengembaliansExport, 'laporan_pengembalian.xlsx');
    }
}