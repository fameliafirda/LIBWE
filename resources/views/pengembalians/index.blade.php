@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div class="container-fluid py-4">
    <!-- Header dengan gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-2" style="font-weight: 600;">📦 Data Pengembalian Buku</h3>
                            <p class="text-white opacity-75 mb-0">Kelola data pengembalian buku perpustakaan</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('pengembalians.create') }}" class="btn btn-light btn-lg" style="border-radius: 50px; padding: 12px 30px; font-weight: 600; color: #667eea; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Pengembalian
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #28a745;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #dc3545;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Pengembalian</p>
                            <h2 class="text-dark mb-0">{{ $pengembalians->count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-undo-alt fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Denda</p>
                            <h2 class="text-dark mb-0">Rp {{ number_format($pengembalians->sum('denda'), 0, ',', '.') }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Rata-rata Keterlambatan</p>
                            <h2 class="text-dark mb-0">{{ number_format($pengembalians->avg('keterlambatan'), 1) }} hari</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-clock fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pengembalian -->
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                    <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Pengembalian
                </h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Cari..." id="searchInput" style="border-radius: 50px 0 0 50px; border: 1px solid #e0e0e0;">
                    <button class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); border-radius: 0 50px 50px 0; color: #000;" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="pengembalianTable">
                    <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Harus Kembali</th>
                            <th>Tgl Pengembalian</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-end">Denda</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalians as $index => $pengembalian)
                        <tr style="vertical-align: middle;">
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #8b5cf6;"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $pengembalian->nama }}</span>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark px-3 py-2">{{ $pengembalian->kelas }}</span></td>
                            <td>
                                <span class="fw-semibold">{{ $pengembalian->judul_buku }}</span>
                            </td>
                            <td>{{ optional($pengembalian->pinjaman)->tanggal_pinjam ? \Carbon\Carbon::parse($pengembalian->pinjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge bg-info text-dark px-3 py-2">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($pengembalian->keterlambatan > 0)
                                    <span class="badge bg-danger text-white px-3 py-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $pengembalian->keterlambatan }} hari
                                    </span>
                                @else
                                    <span class="badge bg-success text-white px-3 py-2">
                                        <i class="fas fa-check me-1"></i>
                                        Tepat waktu
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="fw-bold" style="color: #e74c3c;">
                                    Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pengembalians.edit', $pengembalian->id) }}" 
                                       class="btn btn-sm" 
                                       style="background-color: #ffe066; color: #000; border: none; border-radius: 8px 0 0 8px; padding: 8px 12px;"
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pengembalians.destroy', $pengembalian->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini? Stok buku akan dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm" 
                                                style="background-color: #ff6b6b; color: white; border: none; border-radius: 0 8px 8px 0; padding: 8px 12px;"
                                                data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-undo-alt fa-4x mb-3" style="color: #dfe6e9;"></i>
                                    <h6>Belum ada data pengembalian</h6>
                                    <p class="small">Silakan tambah pengembalian baru</p>
                                    <a href="{{ route('pengembalians.create') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                        <i class="fas fa-plus me-1"></i> Tambah Pengembalian
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if(method_exists($pengembalians, 'links') && $pengembalians->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-end">
                {{ $pengembalians->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Tooltip initialization
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#pengembalianTable tbody tr');
        
        tableRows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            if (text.indexOf(searchValue) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection