<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Buku - Perpustakaan SDN Berat Wetan 1</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 30px 30px;
        }
        
        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .book-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .book-info {
            padding: 20px;
        }
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-author {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 8px;
        }
        
        .book-publisher {
            font-size: 0.8rem;
            color: #999;
            margin-bottom: 10px;
        }
        
        .badge-stok {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .stok-tersedia {
            background: #28a745;
            color: white;
        }
        
        .stok-habis {
            background: #dc3545;
            color: white;
        }
        
        .stok-sedikit {
            background: #ffc107;
            color: #333;
        }
        
        .filter-sidebar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            position: sticky;
            top: 20px;
        }
        
        .popular-book-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background 0.3s ease;
        }
        
        .popular-book-item:hover {
            background: #f8f9fa;
        }
        
        .popular-book-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .popular-book-info {
            flex: 1;
        }
        
        .popular-book-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .popular-book-author {
            font-size: 0.8rem;
            color: #666;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-pinjam {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            transition: transform 0.3s ease;
        }
        
        .btn-pinjam:hover {
            transform: scale(1.05);
            color: white;
        }
        
        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-box input {
            padding: 15px 20px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding-right: 50px;
        }
        
        .search-box button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 0;
            }
            
            .book-img {
                height: 200px;
            }
            
            .filter-sidebar {
                margin-bottom: 20px;
                position: relative;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="fas fa-book-open me-2"></i>Perpustakaan SDN Berat Wetan 1
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('katalog') }}">
                        <i class="fas fa-book"></i> Katalog
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pinjaman.history') }}">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Katalog Buku</h1>
        <p class="lead mb-4">Temukan buku favorit Anda di perpustakaan kami</p>
        
        <!-- Search Form -->
        <div class="search-box">
            <form method="GET" action="{{ route('katalog') }}">
                <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-filter me-2"></i>Filter Kategori
                </h5>
                
                <form method="GET" action="{{ route('katalog') }}" id="filterForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kategori" id="allCategories" value="" 
                                {{ !request('kategori') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="allCategories">
                                Semua Kategori
                            </label>
                        </div>
                    </div>
                    
                    @foreach($kategoris as $kategori)
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kategori" 
                                id="kategori{{ $kategori->id }}" value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'checked' : '' }} 
                                onchange="this.form.submit()">
                            <label class="form-check-label" for="kategori{{ $kategori->id }}">
                                <i class="fas fa-tag me-1"></i>{{ $kategori->nama }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </form>
                
                <hr class="my-4">
                
                <!-- Popular Books -->
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-fire me-2 text-danger"></i>Buku Populer
                </h5>
                <div class="popular-books-list">
                    @forelse($popularBooks as $book)
                        <div class="popular-book-item">
                            @if($book->gambar)
                                <img src="{{ Storage::url($book->gambar) }}" class="popular-book-img" alt="{{ $book->judul }}">
                            @else
                                <img src="https://via.placeholder.com/60x80?text=No+Image" class="popular-book-img" alt="No Image">
                            @endif
                            <div class="popular-book-info">
                                <div class="popular-book-title">{{ Str::limit($book->judul, 30) }}</div>
                                <div class="popular-book-author">{{ $book->penulis }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-chart-line"></i> {{ $book->total_dipinjam ?? 0 }} kali dipinjam
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-book-open"></i>
                            <p class="mb-0 mt-2">Belum ada data buku populer</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Result Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">
                        <i class="fas fa-book me-2"></i>Daftar Buku
                    </h4>
                    @if(request('search'))
                        <p class="text-muted mb-0">Hasil pencarian: "{{ request('search') }}"</p>
                    @endif
                </div>
                <div>
                    <span class="badge bg-primary">{{ $books->total() }} Buku ditemukan</span>
                </div>
            </div>
            
            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="row g-4">
                    @foreach($books as $book)
                        <div class="col-md-6 col-lg-4">
                            <div class="book-card">
                                <div style="position: relative;">
                                    @if($book->gambar)
                                        <img src="{{ Storage::url($book->gambar) }}" class="book-img" alt="{{ $book->judul }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x250?text=No+Cover" class="book-img" alt="No Cover">
                                    @endif
                                    
                                    @php
                                        $stokClass = 'stok-tersedia';
                                        $stokText = 'Tersedia';
                                        if($book->stok <= 0) {
                                            $stokClass = 'stok-habis';
                                            $stokText = 'Habis';
                                        } elseif($book->stok <= 3) {
                                            $stokClass = 'stok-sedikit';
                                            $stokText = 'Sisa ' . $book->stok;
                                        }
                                    @endphp
                                    <span class="badge-stok {{ $stokClass }}">
                                        <i class="fas fa-box"></i> {{ $stokText }}
                                    </span>
                                </div>
                                
                                <div class="book-info">
                                    <h5 class="book-title">{{ $book->judul }}</h5>
                                    <p class="book-author">
                                        <i class="fas fa-user me-1"></i>{{ $book->penulis }}
                                    </p>
                                    <p class="book-publisher">
                                        <i class="fas fa-building me-1"></i>{{ $book->penerbit }} | {{ $book->tahun_terbit }}
                                    </p>
                                    
                                    @if($book->kategori)
                                        <span class="badge bg-secondary mb-3">
                                            <i class="fas fa-tag"></i> {{ $book->kategori->nama }}
                                        </span>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-copy"></i> Stok: {{ $book->stok }}
                                        </small>
                                        
                                        @auth
                                            @if($book->stok > 0)
                                                <button type="button" class="btn btn-pinjam btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#pinjamModal"
                                                        data-book-id="{{ $book->id }}"
                                                        data-book-title="{{ $book->judul }}">
                                                    <i class="fas fa-hand-peace"></i> Pinjam
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-times-circle"></i> Stok Habis
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-pinjam btn-sm">
                                                <i class="fas fa-sign-in-alt"></i> Login to Borrow
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $books->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-3">
                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Buku tidak ditemukan</h4>
                    <p class="text-muted">Maaf, buku yang Anda cari tidak tersedia di perpustakaan kami.</p>
                    <a href="{{ route('katalog') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pinjam Buku -->
<div class="modal fade" id="pinjamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hand-peace me-2"></i>Konfirmasi Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pinjaman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin meminjam buku:</p>
                    <h6 class="fw-bold text-primary" id="modalBookTitle"></h6>
                    <input type="hidden" name="buku_id" id="modalBookId">
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Masa peminjaman adalah 7 hari. Harap kembalikan tepat waktu.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Ya, Pinjam Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container text-center">
        <p class="mb-0">
            <i class="fas fa-copyright me-1"></i> 2024 Perpustakaan SDN Berat Wetan 1 | 
            <i class="fas fa-heart text-danger"></i> Membaca adalah jendela dunia
        </p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Modal pinjam buku
    const pinjamModal = document.getElementById('pinjamModal');
    if (pinjamModal) {
        pinjamModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const bookId = button.getAttribute('data-book-id');
            const bookTitle = button.getAttribute('data-book-title');
            
            const modalBookId = pinjamModal.querySelector('#modalBookId');
            const modalBookTitle = pinjamModal.querySelector('#modalBookTitle');
            
            modalBookId.value = bookId;
            modalBookTitle.textContent = bookTitle;
        });
    }
</script>
</body>
</html>