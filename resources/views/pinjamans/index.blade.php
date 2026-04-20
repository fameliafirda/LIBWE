@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

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
                                <i class="fas fa-hand-holding-heart me-2"></i> Daftar Peminjaman Buku
                            </h3>
                            <p class="text-white opacity-75 mb-0">Kelola data peminjaman buku perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('pinjamans.create') }}" class="btn btn-light" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Peminjaman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #28a745;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- NOTIFIKASI STOK HABIS - Dipercantik -->
    @php
        $bukuHabis = App\Models\Book::where('stok', '<=', 0)->get();
    @endphp
    @if($bukuHabis->count() > 0)
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #f39c12; background-color: #fff3cd;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #f39c12;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-2" style="color: #856404;">⚠️ Perhatian! Stok Buku Habis</strong>
                        <div class="row">
                            @foreach($bukuHabis as $buku)
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-book me-2" style="color: #8b5cf6; font-size: 12px;"></i>
                                    <span class="fw-semibold small">{{ $buku->judul }}</span>
                                    <span class="badge bg-secondary ms-2" style="font-size: 10px;">{{ $buku->kategori->nama ?? 'Tanpa Kategori' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistik Peminjaman -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f7c0ec 0%, #a7bdea 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Total Peminjaman</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $pinjamans->count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-book-reader fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Belum Dikembalikan</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $pinjamans->where('status', 'belum dikembalikan')->count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-clock fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-1" style="opacity: 0.8;">Sudah Dikembalikan</p>
                            <h2 class="text-dark mb-0 fw-bold">{{ $pinjamans->where('status', 'sudah dikembalikan')->count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
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

    <!-- Filter Section -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('pinjamans.index') }}" class="d-flex flex-wrap align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>Filter Kelas:
                            </label>
                            <select name="kelas" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 200px;">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px; padding: 8px 25px;">
                            <i class="fas fa-filter me-2"></i> Terapkan
                        </button>
                        @if(request('kelas'))
                            <a href="{{ route('pinjamans.index') }}" class="btn btn-sm" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                            <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Transaksi Peminjaman
                        </h5>
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari peminjam/buku..." id="searchInput" style="border-radius: 50px 0 0 50px; border: 1px solid #e0e0e0;">
                            <button class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); border-radius: 0 50px 50px 0; color: #000;" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="pinjamanTable" style="min-width: 1200px;">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 150px;">Nama</th>
                                    <th style="width: 100px;">Kelas</th>
                                    <th style="width: 250px;">Judul Buku</th>
                                    <th style="width: 120px;" class="text-center">Tanggal Pinjam</th>
                                    <th style="width: 120px;" class="text-center">Tanggal Kembali</th>
                                    <th style="width: 120px;" class="text-center">Status</th>
                                    <th style="width: 200px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjamans as $i => $pinjaman)
                                    @php
                                        $terlambat = 0;
                                        if($pinjaman->status == 'belum dikembalikan') {
                                            $hariIni = \Carbon\Carbon::now();
                                            $tanggalKembali = \Carbon\Carbon::parse($pinjaman->tanggal_kembali);
                                            if($hariIni->gt($tanggalKembali)) {
                                                $terlambat = $hariIni->diffInDays($tanggalKembali);
                                            }
                                        }
                                    @endphp
                                    <tr style="vertical-align: middle;" class="{{ $terlambat > 0 ? 'bg-danger bg-opacity-10' : '' }}">
                                        <td class="text-center fw-bold">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user" style="color: #8b5cf6;"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $pinjaman->anggota->nama ?? $pinjaman->nama }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark px-3 py-2">{{ $pinjaman->anggota->kelas ?? $pinjaman->kelas }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-book me-2" style="color: #8b5cf6;"></i>
                                                {{ $pinjaman->judul_buku ?? '-' }}
                                            </div>
                                            @php
                                                $buku = App\Models\Book::where('judul', $pinjaman->judul_buku)->first();
                                            @endphp
                                            @if($buku && $buku->stok == 0 && $pinjaman->status == 'belum dikembalikan')
                                                <span class="badge bg-danger mt-1">Stok buku ini habis!</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark px-3 py-2">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark px-3 py-2">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                {{ \Carbon\Carbon::parse($pinjaman->tanggal_kembali)->format('d/m/Y') }}
                                            </span>
                                            @if($terlambat > 0)
                                                <span class="badge bg-danger d-block mt-1">
                                                    Terlambat {{ $terlambat }} hari
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pinjaman->status === 'sudah dikembalikan')
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i> Sudah Dikembalikan
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i> Belum Dikembalikan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                <a href="{{ route('pinjamans.edit', $pinjaman->id) }}"
                                                   class="btn btn-sm"
                                                   style="background-color: #ffe066; color: #000; border: none; border-radius: 8px; padding: 6px 10px;"
                                                   data-bs-toggle="tooltip" title="Edit peminjaman">
                                                   <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($pinjaman->status === 'belum dikembalikan')
                                                    <form action="{{ route('pinjamans.update', $pinjaman->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="sudah dikembalikan">
                                                        <input type="hidden" name="nama" value="{{ $pinjaman->nama }}">
                                                        <input type="hidden" name="kelas" value="{{ $pinjaman->kelas }}">
                                                        <input type="hidden" name="jenis_kelamin" value="{{ $pinjaman->jenis_kelamin }}">
                                                        <input type="hidden" name="judul_buku" value="{{ $pinjaman->judul_buku }}">
                                                        <input type="hidden" name="tanggal_pinjam" value="{{ $pinjaman->tanggal_pinjam }}">
                                                        <input type="hidden" name="tanggal_kembali" value="{{ $pinjaman->tanggal_kembali }}">
                                                        <button type="submit" class="btn btn-sm"
                                                                style="background-color: #8fd19e; color: #000; border: none; border-radius: 8px; padding: 6px 10px;"
                                                                onclick="return confirm('Apakah buku sudah dikembalikan?')"
                                                                data-bs-toggle="tooltip" title="Tandai sudah dikembalikan">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('pinjamans.destroy', $pinjaman->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm"
                                                            style="background-color: #ff6b6b; color: white; border: none; border-radius: 8px; padding: 6px 10px;"
                                                            onclick="return confirm('Yakin ingin menghapus data peminjaman ini? Stok buku akan dikembalikan.')"
                                                            data-bs-toggle="tooltip" title="Hapus peminjaman">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-book-open fa-4x mb-3" style="color: #dfe6e9;"></i>
                                                <h6>Belum ada data peminjaman</h6>
                                                <p class="small mb-3">Silakan tambah peminjaman baru</p>
                                                <a href="{{ route('pinjamans.create') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                                    <i class="fas fa-plus-circle me-1"></i> Tambah Peminjaman
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(method_exists($pinjamans, 'links') && $pinjamans->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $pinjamans->firstItem() ?? 0 }} - {{ $pinjamans->lastItem() ?? 0 }} dari {{ $pinjamans->total() ?? 0 }} data
                        </div>
                        <div>
                            {{ $pinjamans->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
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
        let tableRows = document.querySelectorAll('#pinjamanTable tbody tr');
        
        tableRows.forEach(function(row) {
            if (row.querySelector('td[colspan="8"]')) return; // Skip empty state row
            
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