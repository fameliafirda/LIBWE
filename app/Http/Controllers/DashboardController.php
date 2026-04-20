<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Category;
use App\Models\Book;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');

        // ===========================================
        // DATA KATEGORI BUKU
        // ===========================================
        $categories = Category::withCount('books')->get();
        $kategoriNama = $categories->pluck('nama');
        $kategoriJumlah = $categories->pluck('books_count');

        // ===========================================
        // DATA PEMINJAMAN PER BULAN
        // ===========================================
        $monthlyBorrows = Pinjaman::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as count')
            ->whereYear('tanggal_pinjam', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ===========================================
        // DATA BUKU DITAMBAHKAN PER BULAN
        // ===========================================
        $monthlyBooks = Book::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ===========================================
        // SIAPKAN DATA UNTUK CHART
        // ===========================================
        $allMonths = [];
        $peminjamanData = [];
        $bukuData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->locale('id')->monthName;
            $allMonths[] = $monthName;
            $peminjamanData[$i] = 0;
            $bukuData[$i] = 0;
        }

        foreach ($monthlyBorrows as $borrow) {
            $peminjamanData[$borrow->month] = $borrow->count;
        }

        foreach ($monthlyBooks as $book) {
            $bukuData[$book->month] = $book->count;
        }

        $chartPeminjamanData = array_values($peminjamanData);
        $chartBukuData = array_values($bukuData);

        // ===========================================
        // STATISTIK TAMBAHAN
        // ===========================================
        $totalBuku = Book::count();
        $totalBukuTahunIni = Book::whereYear('created_at', $currentYear)->count();
        
        $totalPeminjaman = Pinjaman::count();
        $peminjamanAktif = Pinjaman::where('status', 'belum dikembalikan')->count();

        return view('dashboard', [
            'kategoriNama' => $kategoriNama,
            'kategoriJumlah' => $kategoriJumlah,
            'monthlyLabels' => $allMonths,
            'peminjamanData' => $chartPeminjamanData,
            'bukuData' => $chartBukuData,
            'currentYear' => $currentYear,
            'totalBuku' => $totalBuku,
            'totalBukuTahunIni' => $totalBukuTahunIni,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanAktif' => $peminjamanAktif,
        ]);
    }
}