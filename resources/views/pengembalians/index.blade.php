@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-8">
                            <h3 class="text-white mb-2" style="font-weight: 600;">📦 Data Pengembalian Buku</h3>
                            <p class="text-white opacity-75 mb-0">Kelola data pengembalian buku perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('pengembalians.create') }}" class="btn btn-light btn-lg" style="border-radius: 50px; padding: 12px 30px; font-weight: 600; color: #667eea; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Pengembalian
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <div class="row mb-4">
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Pengembalian</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ isset($pengembalians) ? $pengembalians->count() : 0 }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-undo-alt fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Denda</p>
                            <h2 class="text-dark mb-0 fw-bold">Rp {{ isset($pengembalians) ? number_format($pengembalians->sum('denda'), 0, ',', '.') : 0 }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Rata-rata Keterlambatan</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ isset($pengembalians) ? number_format($pengembalians->avg('keterlambatan'), 1) : 0 }} hari</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-clock fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                    <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Pengembalian
                </h5>
                <div class="input-group" style="width: 100%; max-width: 300px;">
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
                            <th>NISN</th>
                            <th class="text-center">Kelas</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Harus Kembali</th>
                            <th class="text-center">Tgl Pengembalian</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-end">Denda</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
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
                            <td>
                                <span class="badge bg-secondary px-2 py-1">
                                    {{ $pengembalian->nisn ?? ($pengembalian->pinjaman->anggota->nisn ?? '-') }}
                                </span>
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark px-3 py-2">{{ $pengembalian->kelas }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-book me-2" style="color: #8b5cf6;"></i>
                                    {{ Str::limit($pengembalian->judul_buku, 30) }}
                                </div>
                            </td>
                            <td class="text-center">{{ optional($pengembalian->pinjaman)->tanggal_pinjam ? \Carbon\Carbon::parse($pengembalian->pinjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">{{ $pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-3 py-2">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($pengembalian->keterlambatan > 0)
                                    <span class="badge bg-danger text-white px-3 py-2">{{ $pengembalian->keterlambatan }} hari</span>
                                @else
                                    <span class="badge bg-success text-white px-3 py-2">Tepat waktu</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-danger">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 25px;">
                                    <a href="{{ route('pengembalians.edit', $pengembalian->id) }}" 
                                       style="color: #f59e0b; font-size: 1.3rem; transition: 0.2s; text-decoration: none;"
                                       onmouseover="this.style.transform='scale(1.2)'"
                                       onmouseout="this.style.transform='scale(1)'"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pengembalians.destroy', $pengembalian->id) }}" 
                                          method="POST" 
                                          class="m-0 p-0"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background: transparent; border: none; color: #ef4444; font-size: 1.3rem; transition: 0.2s; padding: 0; cursor: pointer;"
                                                onmouseover="this.style.transform='scale(1.2)'"
                                                onmouseout="this.style.transform='scale(1)'"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-undo-alt fa-4x mb-3" style="color: #dfe6e9;"></i>
                                    <h6>Belum ada data pengembalian</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#pengembalianTable tbody tr');
        tableRows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            row.style.display = (text.indexOf(searchValue) > -1) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection