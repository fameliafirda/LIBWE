@extends('layouts.app')

@section('title', 'Laporan Buku')

@section('content')
<div class="container-fluid px-4 py-3 laporan-buku-page">
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg laporan-header-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0 20px 20px 0;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-2 laporan-page-title" style="font-weight: 600;">
                                <i class="fas fa-book me-2"></i> Laporan Buku
                            </h3>
                            <p class="text-white opacity-75 mb-0">Rekap data buku perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('laporan.buku.export') }}" class="btn btn-light laporan-header-btn" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-file-excel me-2"></i> Download Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('laporan.buku') }}" class="d-flex flex-wrap align-items-center gap-3 laporan-filter-form">
                        <div class="d-flex align-items-center laporan-filter-label">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>Filter:
                            </label>
                        </div>
                        <div class="d-flex align-items-center laporan-filter-field">
                            <select name="bulan" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 150px;">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center laporan-filter-field">
                            <select name="tahun" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 120px;">
                                <option value="">Semua Tahun</option>
                                @foreach(range(date('Y'), date('Y')-5) as $t)
                                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn laporan-filter-btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px; padding: 8px 25px;">
                            <i class="fas fa-search me-2"></i> Tampilkan
                        </button>
                        @if(request('bulan') || request('tahun'))
                            <a href="{{ route('laporan.buku') }}" class="btn btn-sm laporan-reset-btn" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalBuku = $books->total();
        $totalStok = App\Models\Book::sum('stok');
        $totalKategori = App\Models\Category::count();
        $bukuBaruBulanIni = App\Models\Book::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 laporan-stat-card" style="border-radius: 20px; background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center laporan-stat-inner">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Buku</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $totalBuku }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 laporan-stat-icon">
                            <i class="fas fa-book fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 laporan-stat-card" style="border-radius: 20px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center laporan-stat-inner">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Stok</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $totalStok }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 laporan-stat-icon">
                            <i class="fas fa-boxes fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 laporan-stat-card" style="border-radius: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center laporan-stat-inner">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Jumlah Kategori</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $totalKategori }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 laporan-stat-icon">
                            <i class="fas fa-tags fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 laporan-stat-card" style="border-radius: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center laporan-stat-inner">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Buku Baru Bulan Ini</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $bukuBaruBulanIni }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 laporan-stat-icon">
                            <i class="fas fa-calendar-plus fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <div class="laporan-month-nav">
                        <span class="fw-semibold text-muted laporan-month-label">
                            <i class="fas fa-calendar-alt me-2" style="color: #8b5cf6;"></i>Lompat ke bulan:
                        </span>
                        <div class="laporan-month-scroll">
                            @foreach(range(1,12) as $b)
                                <a href="{{ route('laporan.buku', ['bulan' => $b, 'tahun' => request('tahun', date('Y'))]) }}" 
                                   class="btn btn-sm laporan-month-chip {{ request('bulan') == $b ? 'active' : '' }}" 
                                   style="{{ request('bulan') == $b ? 'background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;' : 'background-color: #f0f0f0; color: #666;' }} border-radius: 50px; padding: 5px 15px;">
                                    {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMM') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $groupedBooks = $books->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m');
        })->sortKeysDesc();
    @endphp

    @forelse($groupedBooks as $bulanTahun => $items)
        @php
            $carbon = \Carbon\Carbon::createFromFormat('Y-m', $bulanTahun);
            $totalBulan = $items->count();
            $totalStokBulan = $items->sum('stok');
        @endphp
        
        <div class="row g-0 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header py-3 px-4 laporan-group-header" style="background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                        <div class="d-flex justify-content-between align-items-center laporan-group-header-inner">
                            <div>
                                <h5 class="mb-0 fw-bold laporan-group-title" style="color: #333;">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ $carbon->locale('id')->isoFormat('MMMM YYYY') }}
                                </h5>
                            </div>
                            <div class="d-flex gap-3 laporan-group-badges">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-chart-line me-1"></i> Total Buku: {{ $totalBulan }}
                                </span>
                                <span class="badge bg-info text-dark px-3 py-2">
                                    <i class="fas fa-boxes me-1"></i> Total Stok: {{ $totalStokBulan }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive laporan-table-wrap">
                            <table class="table table-hover mb-0 laporan-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Tahun Terbit</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Tanggal Ditambahkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $book)
                                        <tr style="vertical-align: middle;">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($book->gambar)
                                                        <img src="{{ asset($book->gambar) }}" 
                                                             width="30" height="40" 
                                                             style="object-fit: cover; border-radius: 5px; margin-right: 10px;"
                                                             alt="cover">
                                                    @else
                                                        <div class="bg-light rounded me-2" style="width: 30px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <span class="fw-semibold">{{ $book->judul }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $book->penulis }}</td>
                                            <td>
                                                <span class="badge" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; padding: 5px 10px;">
                                                    {{ $book->kategori->nama ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark px-3 py-2">
                                                    {{ $book->tahun_terbit }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($book->stok > 5)
                                                    <span class="badge bg-success px-3 py-2">{{ $book->stok }}</span>
                                                @elseif($book->stok > 0)
                                                    <span class="badge bg-warning text-dark px-3 py-2">{{ $book->stok }}</span>
                                                @else
                                                    <span class="badge bg-danger px-3 py-2">Habis</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($book->created_at)->format('d/m/Y H:i') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="row g-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-4x mb-3" style="color: #dfe6e9;"></i>
                        <h5 class="text-muted">Tidak ada data buku</h5>
                        @if(request('bulan') || request('tahun'))
                            <p class="text-muted mb-3">Tidak ada data untuk filter yang dipilih</p>
                            <a href="{{ route('laporan.buku') }}" class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px;">
                                <i class="fas fa-times me-2"></i>Reset Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforelse

    @if(method_exists($books, 'links') && $books->hasPages())
    <div class="row g-0 mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .laporan-buku-page .laporan-month-nav {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .laporan-buku-page .laporan-month-scroll {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .laporan-buku-page .laporan-table-wrap {
        width: 100%;
    }

    @media (max-width: 991.98px) {
        .laporan-buku-page .laporan-filter-form {
            align-items: stretch !important;
        }

        .laporan-buku-page .laporan-filter-label,
        .laporan-buku-page .laporan-filter-field {
            width: 100%;
        }

        .laporan-buku-page .laporan-filter-field select,
        .laporan-buku-page .laporan-filter-btn,
        .laporan-buku-page .laporan-reset-btn {
            width: 100%;
        }

        .laporan-buku-page .laporan-header-btn {
            width: 100%;
            text-align: center;
        }

        .laporan-buku-page .laporan-group-header-inner {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .laporan-buku-page .laporan-group-badges {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px !important;
        }

        .laporan-buku-page .laporan-group-badges .badge {
            white-space: normal;
        }
    }

    @media (max-width: 767.98px) {
        .laporan-buku-page {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .laporan-buku-page .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .laporan-buku-page .card-body.p-4 {
            padding: 1.25rem !important;
        }

        .laporan-buku-page .laporan-page-title {
            font-size: 1.35rem;
            line-height: 1.4;
        }

        .laporan-buku-page .laporan-header-card {
            border-radius: 16px !important;
        }

        .laporan-buku-page .laporan-stat-card .card-body {
            padding: 1rem;
        }

        .laporan-buku-page .laporan-stat-inner {
            align-items: flex-start !important;
            gap: 10px;
        }

        .laporan-buku-page .laporan-stat-icon {
            padding: 0.85rem !important;
        }

        .laporan-buku-page .laporan-stat-icon i {
            font-size: 1.25rem !important;
        }

        .laporan-buku-page .laporan-month-nav {
            flex-direction: column;
            align-items: stretch;
        }

        .laporan-buku-page .laporan-month-label {
            margin-right: 0 !important;
        }

        .laporan-buku-page .laporan-month-scroll {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 6px;
        }

        .laporan-buku-page .laporan-month-scroll::-webkit-scrollbar {
            height: 5px;
        }

        .laporan-buku-page .laporan-month-scroll::-webkit-scrollbar-thumb {
            background: #cfcfcf;
            border-radius: 10px;
        }

        .laporan-buku-page .laporan-month-chip {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .laporan-buku-page .laporan-group-title {
            font-size: 1rem;
        }

        .laporan-buku-page .laporan-table {
            min-width: 760px;
        }

        .laporan-buku-page .table th,
        .laporan-buku-page .table td {
            white-space: nowrap;
            font-size: 0.92rem;
        }

        .laporan-buku-page .table td .fw-semibold {
            white-space: normal;
        }
    }

    @media (max-width: 575.98px) {
        .laporan-buku-page .card-header.py-3.px-4,
        .laporan-buku-page .card-body.p-3,
        .laporan-buku-page .card-body.p-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .laporan-buku-page .laporan-page-title {
            font-size: 1.15rem;
        }

        .laporan-buku-page .laporan-header-btn,
        .laporan-buku-page .laporan-filter-btn,
        .laporan-buku-page .laporan-reset-btn {
            width: 100%;
        }

        .laporan-buku-page .badge {
            font-size: 0.75rem;
        }
    }
</style>
@endpush