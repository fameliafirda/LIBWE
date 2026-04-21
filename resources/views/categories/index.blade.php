@extends('layouts.app')

@section('title', 'Daftar Kategori')

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
                                <i class="fas fa-tags me-2"></i> Daftar Kategori Buku
                            </h3>
                            <p class="text-white opacity-75 mb-0">Kelola kategori buku perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('categories.create') }}" class="btn btn-light" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Kategori
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
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

    <!-- Notifikasi Stok Habis -->
    @php
        $bukuHabis = App\Models\Book::where('stok', '<=', 0)->get();
    @endphp
    @if($bukuHabis->count() > 0)
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #f39c12;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #f39c12;"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-2">⚠️ Perhatian! Stok Buku Habis</strong>
                        <ul class="mb-0 ps-3">
                            @foreach($bukuHabis as $buku)
                                <li class="mb-1">
                                    <span class="fw-semibold">{{ $buku->judul }}</span> 
                                    <span class="badge bg-secondary ms-2">{{ $buku->kategori->nama ?? 'Tanpa Kategori' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Kategori -->
    @if(isset($filterKategori))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center" style="border-radius: 15px; background: rgba(139, 92, 246, 0.1); border-left: 5px solid #8b5cf6;">
                <div>
                    <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>
                    Menampilkan buku kategori: <strong class="ms-1">{{ $filterKategori }}</strong>
                </div>
                <a href="{{ route('books.index') }}" class="btn btn-sm" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 5px 15px;">
                    <i class="fas fa-times me-1"></i> Reset
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('books.index') }}" class="d-flex flex-wrap align-items-center gap-3">
                        <div class="d-flex align-items-center flex-grow-1">
                            <label class="fw-semibold text-muted mb-0 me-3" style="min-width: 70px;">
                                <i class="fas fa-search me-2" style="color: #8b5cf6;"></i>Cari:
                            </label>
                            <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="{{ request('search') }}" style="border-radius: 50px; border: 1px solid #e0e0e0; max-width: 300px;">
                        </div>
                        <div class="d-flex align-items-center">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-tags me-2" style="color: #8b5cf6;"></i>Kategori:
                            </label>
                            <select name="kategori" class="form-control" style="border-radius: 50px; border: 1px solid #e0e0e0; min-width: 200px;">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; border-radius: 50px; padding: 8px 25px;">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                        @if(request('search') || request('kategori'))
                            <a href="{{ route('books.index') }}" class="btn btn-sm" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                            <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Kategori
                        </h5>
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari kategori..." id="searchInput" style="border-radius: 50px 0 0 50px; border: 1px solid #e0e0e0;">
                            <button class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); border-radius: 0 50px 50px 0; color: #000;" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="categoryTable">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Nama Kategori</th>
                                    <th class="text-center" style="width: 150px;">Jumlah Buku</th>
                                    <th class="text-center" style="width: 200px;">Info Stok</th>
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                <tr style="vertical-align: middle;">
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-folder" style="color: #8b5cf6;"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $category->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; padding: 6px 15px; font-size: 14px;">
                                            {{ $category->books_count }} buku
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $totalStok = 0;
                                            $bukuHabis = 0;
                                            foreach($category->books as $buku) {
                                                $totalStok += $buku->stok;
                                                if($buku->stok == 0) $bukuHabis++;
                                            }
                                        @endphp
                                        <div class="d-flex justify-content-center gap-2">
                                            <span class="badge bg-info text-white px-3 py-2" data-bs-toggle="tooltip" title="Total stok tersedia">
                                                <i class="fas fa-boxes me-1"></i> {{ $totalStok }}
                                            </span>
                                            @if($bukuHabis > 0)
                                                <span class="badge bg-danger px-3 py-2" data-bs-toggle="tooltip" title="Buku dengan stok habis">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $bukuHabis }} habis
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.edit', $category->id) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #ffe066; color: #000; border: none; border-radius: 8px 0 0 8px; padding: 6px 12px;"
                                               data-bs-toggle="tooltip" title="Edit kategori">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('books.index', ['kategori' => $category->id]) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #8fd19e; color: #000; border: none; border-radius: 0; padding: 6px 12px;"
                                               data-bs-toggle="tooltip" title="Lihat buku dalam kategori ini">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua buku dalam kategori ini akan kehilangan kategori.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm" 
                                                        style="background-color: #ff6b6b; color: white; border: none; border-radius: 0 8px 8px 0; padding: 6px 12px;"
                                                        data-bs-toggle="tooltip" title="Hapus kategori">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-4x mb-3" style="color: #dfe6e9;"></i>
                                            <h6>Belum ada data kategori</h6>
                                            <p class="small mb-3">Silakan tambah kategori baru</p>
                                            <a href="{{ route('categories.create') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Kategori
                                            </a>
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
    </div>

    <!-- Daftar Buku per Kategori (Accordion) -->
    @if($categories->count() > 0)
    <div class="row g-0 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                        <i class="fas fa-chevron-circle-down me-2" style="color: #8b5cf6;"></i> Detail Stok Buku per Kategori
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="accordionKategori">
                        @foreach($categories as $index => $category)
                            @if($category->books_count > 0)
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" style="background-color: #f8f9fa;">
                                        <div class="d-flex align-items-center w-100">
                                            <i class="fas fa-folder me-3" style="color: #8b5cf6;"></i>
                                            <span class="fw-semibold me-3">{{ $category->nama }}</span>
                                            <span class="badge bg-info me-2">{{ $category->books_count }} buku</span>
                                            @php
                                                $totalStok = $category->books->sum('stok');
                                                $bukuHabis = $category->books->where('stok', 0)->count();
                                            @endphp
                                            <span class="badge bg-success me-2">Stok: {{ $totalStok }}</span>
                                            @if($bukuHabis > 0)
                                                <span class="badge bg-danger">Habis: {{ $bukuHabis }}</span>
                                            @endif
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordionKategori">
                                    <div class="accordion-body p-0">
                                        <table class="table table-sm mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Judul Buku</th>
                                                    <th class="text-center" style="width: 100px;">Stok</th>
                                                    <th class="text-center" style="width: 100px;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($category->books as $buku)
                                                <tr>
                                                    <td class="ps-4">
                                                        <i class="fas fa-book me-2" style="color: #8b5cf6; font-size: 12px;"></i>
                                                        {{ $buku->judul }}
                                                    </td>
                                                    <td class="text-center">{{ $buku->stok }}</td>
                                                    <td class="text-center">
                                                        @if($buku->stok > 5)
                                                            <span class="badge bg-success">Tersedia</span>
                                                        @elseif($buku->stok > 0)
                                                            <span class="badge bg-warning text-dark">Sisa {{ $buku->stok }}</span>
                                                        @else
                                                            <span class="badge bg-danger">Habis</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
        let tableRows = document.querySelectorAll('#categoryTable tbody tr');
        
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