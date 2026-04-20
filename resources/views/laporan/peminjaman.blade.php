@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Gradient -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0 20px 20px 0;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-2" style="font-weight: 600;">
                                <i class="fas fa-hand-holding-heart me-2"></i> Laporan Peminjaman
                            </h3>
                            <p class="text-white opacity-75 mb-0">Rekap data peminjaman buku perpustakaan</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('laporan.peminjaman.export') }}" class="btn btn-light" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-file-excel me-2"></i> Download Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('laporan.peminjaman') }}" class="d-flex flex-wrap align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>Filter:
                            </label>
                        </div>
                        <div class="d-flex align-items-center">
                            <select name="bulan" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 150px;">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <select name="tahun" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 120px;">
                                <option value="">Semua Tahun</option>
                                @foreach(range(date('Y'), date('Y')-5) as $t)
                                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <select name="status" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 150px;">
                                <option value="">Semua Status</option>
                                <option value="belum dikembalikan" {{ request('status') == 'belum dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                                <option value="sudah dikembalikan" {{ request('status') == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px; padding: 8px 25px;">
                            <i class="fas fa-search me-2"></i> Tampilkan
                        </button>
                        @if(request('bulan') || request('tahun') || request('status'))
                            <a href="{{ route('laporan.peminjaman') }}" class="btn btn-sm" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Peminjaman -->
    @php
        $totalPeminjaman = $pinjamans->count();
        $belumDikembalikan = $pinjamans->where('status', 'belum dikembalikan')->count();
        $sudahDikembalikan = $pinjamans->where('status', 'sudah dikembalikan')->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Peminjaman</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $totalPeminjaman }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-book-reader fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Belum Dikembalikan</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $belumDikembalikan }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-clock fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Sudah Dikembalikan</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $sudahDikembalikan }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Anggota</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ App\Models\Anggota::count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Bulanan -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="fw-semibold text-muted me-3">
                            <i class="fas fa-calendar-alt me-2" style="color: #8b5cf6;"></i>Lompat ke bulan:
                        </span>
                        @foreach(range(1,12) as $b)
                            <a href="{{ route('laporan.peminjaman', ['bulan' => $b, 'tahun' => request('tahun', date('Y'))]) }}" 
                               class="btn btn-sm {{ request('bulan') == $b ? 'active' : '' }}" 
                               style="{{ request('bulan') == $b ? 'background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;' : 'background-color: #f0f0f0; color: #666;' }} border-radius: 50px; padding: 5px 15px;">
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMM') }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Peminjaman per Bulan -->
    @php
        $groupedPinjamans = $pinjamans->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->tanggal_pinjam)->format('Y-m');
        })->sortKeysDesc();
    @endphp

    @forelse($groupedPinjamans as $bulanTahun => $items)
        @php
            $carbon = \Carbon\Carbon::createFromFormat('Y-m', $bulanTahun);
            $totalBulan = $items->count();
            $dipinjam = $items->where('status', 'belum dikembalikan')->count();
            $dikembalikan = $items->where('status', 'sudah dikembalikan')->count();
        @endphp
        
        <div class="row g-0 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #333;">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ $carbon->locale('id')->isoFormat('MMMM YYYY') }}
                                </h5>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-chart-line me-1"></i> Total: {{ $totalBulan }}
                                </span>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-clock me-1"></i> Dipinjam: {{ $dipinjam }}
                                </span>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i> Kembali: {{ $dikembalikan }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Judul Buku</th>
                                        <th class="text-center">Tanggal Pinjam</th>
                                        <th class="text-center">Tanggal Kembali</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $pinjaman)
                                        <tr style="vertical-align: middle;">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user me-2" style="color: #8b5cf6;"></i>
                                                    {{ $pinjaman->nama }}
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark">{{ $pinjaman->kelas }}</span></td>
                                            <td>
                                                <i class="fas fa-book me-2" style="color: #8b5cf6;"></i>
                                                {{ $pinjaman->judul_buku }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark">
                                                    {{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($pinjaman->tanggal_kembali)
                                                    <span class="badge bg-info text-dark">
                                                        {{ \Carbon\Carbon::parse($pinjaman->tanggal_kembali)->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($pinjaman->status == 'sudah dikembalikan')
                                                    <span class="badge bg-success">Sudah Dikembalikan</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum Dikembalikan</span>
                                                @endif
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
                        <i class="fas fa-inbox fa-4x mb-3" style="color: #dfe6e9;"></i>
                        <h5 class="text-muted">Tidak ada data peminjaman</h5>
                        @if(request('bulan') || request('tahun') || request('status'))
                            <p class="text-muted mb-3">Tidak ada data untuk filter yang dipilih</p>
                            <a href="{{ route('laporan.peminjaman') }}" class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px;">
                                <i class="fas fa-times me-2"></i>Reset Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection