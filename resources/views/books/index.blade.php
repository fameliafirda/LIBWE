@extends('layouts.app')

@section('title', 'Daftar Buku')

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
                                <i class="fas fa-book me-2"></i> Daftar Buku Perpustakaan
                            </h3>
                            <p class="text-white opacity-75 mb-0">Kelola koleksi buku perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('books.create') }}" class="btn btn-light" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Buku
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
                                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
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

    <!-- Tabel Buku -->
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                            <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Koleksi Buku
                            <span class="badge ms-2" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">{{ $books->total() }} buku</span>
                        </h5>
                        <span class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Halaman {{ $books->currentPage() }} dari {{ $books->lastPage() }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="min-width: 1200px;">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th style="width: 250px;">Judul</th>
                                    <th style="width: 200px;">Penulis</th>
                                    <th style="width: 150px;">Kategori</th>
                                    <th style="width: 100px;" class="text-center">Tahun Terbit</th>
                                    <th style="width: 100px;" class="text-center">Stok</th>
                                    <th style="width: 100px;" class="text-center">Cover</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $index => $book)
                                <tr style="vertical-align: middle;">
                                    <td class="text-center fw-bold">{{ $books->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-book" style="color: #8b5cf6;"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $book->judul }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-edit me-2" style="color: #f472b6;"></i>
                                            {{ $book->penulis }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000; padding: 6px 12px;">
                                            {{ $book->kategori->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark px-3 py-2">
                                            <i class="fas fa-calendar-alt me-1" style="color: #8b5cf6;"></i>
                                            {{ $book->tahun_terbit }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($book->stok > 5)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> {{ $book->stok }}
                                            </span>
                                        @elseif($book->stok > 0)
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $book->stok }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i> Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($book->gambar)
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ asset('storage/' . $book->gambar) }}" 
                                                     width="50" 
                                                     height="70" 
                                                     alt="Cover {{ $book->judul }}"
                                                     style="object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
                                                     data-bs-toggle="tooltip" 
                                                     data-bs-placement="top" 
                                                     title="{{ $book->judul }}">
                                            </div>
                                        @else
                                            <div class="bg-light d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 70px; border-radius: 8px; border: 1px dashed #ccc;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('books.edit', $book->id) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #ffe066; color: #000; border: none; border-radius: 8px 0 0 8px; padding: 8px 12px;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus buku ini? Data terkait seperti peminjaman mungkin terpengaruh.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm" 
                                                        style="background-color: #ff6b6b; color: white; border: none; border-radius: 0 8px 8px 0; padding: 8px 12px;">
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
                                            <h6>Belum ada data buku</h6>
                                            <p class="small mb-3">Silakan tambah buku baru</p>
                                            <a href="{{ route('books.create') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Buku
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if(method_exists($books, 'links') && $books->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $books->firstItem() }} - {{ $books->lastItem() }} dari {{ $books->total() }} buku
                        </div>
                        <div>
                            {{ $books->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tooltip initialization
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush