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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f0f2f5;
        }
        
        /* Navbar */
        .navbar {
            background: #2c3e50;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #ecf0f1 !important;
        }
        
        .navbar-brand i {
            color: #3498db;
        }
        
        .nav-link {
            color: #ecf0f1 !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #3498db !important;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            color: white;
            margin-bottom: 40px;
        }
        
        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card i {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .stat-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 10px 0;
            color: #2c3e50;
        }
        
        .stat-card p {
            color: #7f8c8d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Search Box */
        .search-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .search-input-group {
            display: flex;
            gap: 10px;
        }
        
        .search-input-group input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }
        
        .search-input-group button {
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .search-input-group button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }
        
        /* Category Filter */
        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .category-btn {
            padding: 8px 20px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            color: #666;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .category-btn:hover,
        .category-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }
        
        /* Book Card */
        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .book-cover {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .book-cover .no-cover {
            font-size: 3rem;
            color: rgba(255,255,255,0.5);
        }
        
        .book-number {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .stok-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .stok-tersedia {
            background: #27ae60;
            color: white;
        }
        
        .stok-sedikit {
            background: #f39c12;
            color: white;
        }
        
        .stok-habis {
            background: #e74c3c;
            color: white;
        }
        
        .book-info {
            padding: 20px;
            flex: 1;
        }
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
            line-height: 1.4;
        }
        
        .book-author {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .book-category {
            display: inline-block;
            padding: 3px 10px;
            background: #ecf0f1;
            border-radius: 15px;
            font-size: 0.7rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .book-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
        }
        
        .book-stok {
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        .book-stok i {
            margin-right: 5px;
        }
        
        .btn-pinjam {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-pinjam:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(102,126,234,0.4);
        }
        
        .btn-pinjam-disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        /* Popular Books Section */
        .popular-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .popular-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #3498db;
            display: inline-block;
        }
        
        .popular-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .popular-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .popular-item:hover {
            background: #e8f4f8;
            transform: translateX(5px);
        }
        
        .popular-number {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .popular-info {
            flex: 1;
        }
        
        .popular-book-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .popular-book-author {
            font-size: 0.75rem;
            color: #7f8c8d;
        }
        
        .popular-stats {
            font-size: 0.7rem;
            color: #3498db;
        }
        
        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }
        
        .page-link {
            color: #667eea;
            border-radius: 8px;
            margin: 0 3px;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
        }
        
        /* Modal */
        .modal-content {
            border-radius: 15px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .btn-close-white {
            filter: brightness(0) invert(1);
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.5rem;
            }
            
            .stat-card h3 {
                font-size: 1.3rem;
            }
            
            .search-input-group {
                flex-direction: column;
            }
            
            .category-filter {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-book-open me-2"></i>Perpustakaan SDN Berat Wetan 1
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fas fa-home me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('katalog') }}">
                        <i class="fas fa-book me-1"></i> Katalog
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pinjaman.history') }}">
                        <i class="fas fa-history me-1"></i> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <i class="fas fa-book me-2"></i>Katalog Buku
                </h1>
                <p class="hero-subtitle">
                    Koleksi Buku Perpustakaan SDN Berat Wetan 1
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-book"></i>
                <h3>{{ $totalBooks ?? 0 }}</h3>
                <p>Total Buku</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $availableBooks ?? 0 }}</h3>
                <p>Buku Tersedia</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-hand-peace"></i>
                <h3>{{ $totalActiveLoans ?? 0 }}</h3>
                <p>Dipinjam Aktif</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>{{ $totalLoansThisMonth ?? 0 }}</h3>
                <p>Peminjaman Bulan Ini</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="search-card">
        <form method="GET" action="{{ route('katalog') }}" id="searchForm">
            <div class="search-input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
                <button type="submit">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
            
            <div class="category-filter">
                <button type="button" class="category-btn {{ !request('kategori') ? 'active' : '' }}" data-category="">
                    Semua Kategori
                </button>
                @foreach($kategoris as $kategori)
                    <button type="button" class="category-btn {{ request('kategori') == $kategori->id ? 'active' : '' }}" data-category="{{ $kategori->id }}">
                                        {{ $kategori->nama }}
                    </button>
                @endforeach
            </div>
            <input type="hidden" name="kategori" id="selectedCategory" value="{{ request('kategori') }}">
        </form>
    </div>

    <!-- Popular Books Section -->
    @if(isset($recommendedBooks) && $recommendedBooks->count() > 0)
    <div class="popular-section">
        <h4 class="popular-title">
            <i class="fas fa-trophy me-2" style="color: #f39c12;"></i>Top {{ $recommendedBooks->count() }} Paling Populer
        </h4>
        <p class="text-muted mb-3">Buku yang paling sering dipinjam murid (diurutkan dari tertinggi)</p>
        <div class="popular-list">
            @foreach($recommendedBooks as $index => $book)
            <div class="popular-item">
                <div class="popular-number">#{{ $index + 1 }}</div>
                <div class="popular-info">
                    <div class="popular-book-title">{{ $book->judul }}</div>
                    <div class="popular-book-author">{{ $book->penulis }}</div>
                    <div class="popular-stats">
                        <i class="fas fa-chart-line"></i> {{ $book->total_pinjam ?? 0 }} kali dipinjam
                    </div>
                </div>
                @if($book->stok > 0)
                    <button class="btn-pinjam" onclick="openPinjamModal({{ $book->id }}, '{{ addslashes($book->judul) }}')">
                        <i class="fas fa-hand-peace"></i> Pinjam
                    </button>
                @else
                    <button class="btn-pinjam btn-pinjam-disabled" disabled>
                        <i class="fas fa-times-circle"></i> Habis
                    </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Books Grid -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-muted">
                    <i class="fas fa-list me-2"></i>Total {{ $books->total() }} buku
                </h5>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($books as $index => $book)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="book-card">
                <div class="book-cover">
                    @if($book->gambar && Storage::exists($book->gambar))
                        <img src="{{ Storage::url($book->gambar) }}" alt="{{ $book->judul }}">
                    @else
                        <div class="no-cover">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    <div class="book-number">#{{ $books->firstItem() + $index }}</div>
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
                    <span class="stok-badge {{ $stokClass }}">{{ $stokText }}</span>
                </div>
                <div class="book-info">
                    <h5 class="book-title">{{ Str::limit($book->judul, 40) }}</h5>
                    <p class="book-author">
                        <i class="fas fa-user me-1"></i>{{ $book->penulis }}
                    </p>
                    @if($book->kategori)
                        <span class="book-category">
                            <i class="fas fa-tag me-1"></i>{{ $book->kategori->nama }}
                        </span>
                    @endif
                    <div class="book-stats">
                        <span class="book-stok">
                            <i class="fas fa-copy"></i> Sisa {{ $book->stok }}
                        </span>
                        @auth
                            @if($book->stok > 0)
                                <button class="btn-pinjam" onclick="openPinjamModal({{ $book->id }}, '{{ addslashes($book->judul) }}')">
                                    <i class="fas fa-hand-peace"></i> Pinjam
                                </button>
                            @else
                                <button class="btn-pinjam btn-pinjam-disabled" disabled>
                                    <i class="fas fa-times-circle"></i> Habis
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-pinjam" style="text-decoration: none; display: inline-block;">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Buku tidak ditemukan</h4>
                <p class="text-muted">Maaf, buku yang Anda cari tidak tersedia.</p>
                <a href="{{ route('katalog') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
</div>

<!-- Modal Pinjam Buku -->
<div class="modal fade" id="pinjamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hand-peace me-2"></i>Konfirmasi Peminjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
<footer class="footer">
    <div class="container">
        <p class="mb-0">
            <i class="fas fa-copyright me-1"></i> 2024 Perpustakaan SDN Berat Wetan 1 | 
            <i class="fas fa-heart text-danger"></i> Membaca adalah jendela dunia
        </p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Category filter
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');
            document.getElementById('selectedCategory').value = categoryId;
            document.getElementById('searchForm').submit();
        });
    });
    
    // Pinjam modal
    function openPinjamModal(bookId, bookTitle) {
        const modal = new bootstrap.Modal(document.getElementById('pinjamModal'));
        document.getElementById('modalBookId').value = bookId;
        document.getElementById('modalBookTitle').innerHTML = bookTitle;
        modal.show();
    }
    
    // Auto submit search on enter
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
</script>
</body>
</html>