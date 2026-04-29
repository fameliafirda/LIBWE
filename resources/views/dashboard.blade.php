@extends('layouts.app')

@section('title', '')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center font-weight-bold" style="color: #2d3436;">📊 Dashboard Perpustakaan</h2>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2">Total Buku</h6>
                            <h3 class="mb-0">{{ $totalBuku }}</h3>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2">Buku Baru {{ $currentYear }}</h6>
                            <h3 class="mb-0">{{ $totalBukuTahunIni }}</h3>
                        </div>
                        <i class="fas fa-calendar-plus fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2">Total Peminjaman</h6>
                            <h3 class="mb-0">{{ $totalPeminjaman }}</h3>
                        </div>
                        <i class="fas fa-hand-holding-heart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2">Peminjaman Aktif</h6>
                            <h3 class="mb-0">{{ $peminjamanAktif }}</h3>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">📈 Aktivitas Perpustakaan Tahun {{ $currentYear }}</h5>
                </div>
                <div class="card-body">
                    @if(array_sum($peminjamanData) > 0 || array_sum($bukuData) > 0)
                        <div style="position: relative; height: 300px;">
                            <canvas id="activityChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            Belum ada data aktivitas untuk tahun ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">📚 Buku per Kategori</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">📖 Buku Terbaru</h5>
                    <a href="{{ route('books.index') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Ditambahkan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Book::with('kategori')->latest()->limit(5)->get() as $buku)
                                <tr>
                                    <td>{{ $buku->judul }}</td>
                                    <td>{{ $buku->penulis }}</td>
                                    <td>{{ $buku->kategori->nama ?? '-' }}</td>
                                    <td>
                                        @if($buku->stok > 0)
                                            <span class="badge bg-success">{{ $buku->stok }}</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($buku->created_at)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Kategori Buku (Pie Chart)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($kategoriNama),
                datasets: [{
                    data: @json($kategoriJumlah),
                    backgroundColor: [
                        '#ff9ff3', '#feca57', '#ff6b6b', '#48dbfb',
                        '#1dd1a1', '#f368e0', '#ff9f43', '#10ac84',
                        '#00d2d3', '#5f27cd', '#c44569', '#54a0ff'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    }
                },
                cutout: '60%',
            }
        });
    }

    // Grafik Aktivitas (2 dataset)
    @if(array_sum($peminjamanData) > 0 || array_sum($bukuData) > 0)
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'Peminjaman',
                        data: @json($peminjamanData),
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        borderColor: '#ec4899',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ec4899',
                        pointRadius: 4
                    },
                    {
                        label: 'Buku Baru',
                        data: @json($bukuData),
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderColor: '#8b5cf6',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#8b5cf6',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    }
    @endif
</script>
@endpush