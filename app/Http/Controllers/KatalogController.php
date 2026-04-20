<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Buku Futuristik - Perpustakaan SDN Berat Wetan 1</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #0a0e27;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 0, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Futuristic Navbar */
        .navbar {
            background: rgba(10, 14, 39, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 255, 255, 0.2);
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            color: #fff !important;
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00ffff, #ff00ff);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            width: 80%;
        }
        
        .nav-link:hover {
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }
        
        /* Hero Section Futuristik */
        .hero-section {
            position: relative;
            padding: 80px 0;
            overflow: hidden;
            margin-bottom: 50px;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00ffff, #ff00ff, #00ffff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradientShift 3s ease infinite;
            margin-bottom: 20px;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .hero-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        /* Search Box Futuristik */
        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-box input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.3);
            border-radius: 50px;
            padding: 15px 25px;
            color: white;
            font-size: 1rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .search-box input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
            outline: none;
        }
        
        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .search-box button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .search-box button:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
        }
        
        /* Floating Orbs */
        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
            z-index: 1;
        }
        
        .orb-1 {
            width: 300px;
            height: 300px;
            background: #00ffff;
            top: -150px;
            left: -100px;
            animation-delay: 0s;
        }
        
        .orb-2 {
            width: 400px;
            height: 400px;
            background: #ff00ff;
            bottom: -200px;
            right: -150px;
            animation-delay: 5s;
        }
        
        .orb-3 {
            width: 200px;
            height: 200px;
            background: #ffaa00;
            top: 50%;
            left: 50%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        /* Sidebar Futuristik */
        .filter-sidebar {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 20px;
            padding: 25px;
            position: sticky;
            top: 100px;
            transition: all 0.3s ease;
        }
        
        .filter-sidebar:hover {
            border-color: rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.1);
        }
        
        .sidebar-title {
            color: #00ffff;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(0, 255, 255, 0.3);
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #00ffff;
            border-color: #00ffff;
            box-shadow: 0 0 10px #00ffff;
        }
        
        .form-check:hover .form-check-label {
            color: #00ffff;
        }
        
        /* Book Card Futuristik */
        .book-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            position: relative;
        }
        
        .book-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .book-card:hover::before {
            left: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #00ffff;
            box-shadow: 0 20px 40px rgba(0, 255, 255, 0.2);
        }
        
        .book-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.5s ease;
        }
        
        .book-card:hover .book-img {
            transform: scale(1.05);
        }
        
        .book-info {
            padding: 20px;
        }
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-author {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
        }
        
        .book-publisher {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 10px;
        }
        
        /* Stok Badge Futuristik */
        .badge-stok {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
            z-index: 2;
        }
        
        .stok-tersedia {
            background: rgba(0, 255, 0, 0.8);
            color: white;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .stok-habis {
            background: rgba(255, 0, 0, 0.8);
            color: white;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        .stok-sedikit {
            background: rgba(255, 170, 0, 0.8);
            color: #000;
            box-shadow: 0 0 10px rgba(255, 170, 0, 0.5);
        }
        
        /* Button Futuristik */
        .btn-pinjam {
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-pinjam:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            color: white;
        }
        
        /* Popular & Recommended Books */
        .popular-book-item, .recommendation-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-bottom: 1px solid rgba(0, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .popular-book-item:hover, .recommendation-item:hover {
            background: rgba(0, 255, 255, 0.1);
            border-radius: 10px;
            transform: translateX(5px);
        }
        
        .popular-book-img, .recommendation-img {
            width: 55px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .popular-book-info, .recommendation-info {
            flex: 1;
        }
        
        .popular-book-title, .recommendation-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
        }
        
        .popular-book-author, .recommendation-author {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Alert Futuristik */
        .alert-futuristic {
            background: rgba(0, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid #00ffff;
            border-radius: 15px;
            color: #00ffff;
        }
        
        .alert-futuristic-success {
            background: rgba(0, 255, 0, 0.1);
            border-color: #00ff00;
            color: #00ff00;
        }
        
        .alert-futuristic-danger {
            background: rgba(255, 0, 0, 0.1);
            border-color: #ff0000;
            color: #ff4444;
        }
        
        /* Pagination Futuristik */
        .pagination {
            justify-content: center;
            gap: 10px;
        }
        
        .pagination .page-item .page-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.3);
            color: #fff;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            border-color: transparent;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
        }
        
        .pagination .page-item .page-link:hover {
            background: rgba(0, 255, 255, 0.2);
            border-color: #00ffff;
            transform: translateY(-2px);
        }
        
        /* Modal Futuristik */
        .modal-content {
            background: rgba(10, 14, 39, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 255, 0.3);
            border-radius: 20px;
            color: white;
        }
        
        .modal-header {
            border-bottom-color: rgba(0, 255, 255, 0.2);
        }
        
        .modal-footer {
            border-top-color: rgba(0, 255, 255, 0.2);
        }
        
        .btn-close {
            filter: invert(1);
        }
        
        /* Footer Futuristik */
        .footer {
            background: rgba(10, 14, 39, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0, 255, 255, 0.2);
            margin-top: 60px;
            padding: 30px 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-section {
                padding: 50px 0;
            }
            
            .book-img {
                height: 200px;
            }
            
            .filter-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 30px;
            }
        }
        
        /* Glowing Text */
        .glow-text {
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }
        
        /* Stat Counter */
        .stat-badge {
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0a0e27;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Floating Orbs -->
<div class="floating-orb orb-1"></div>
<div class="floating-orb orb-2"></div>
<div class="floating-orb orb-3"></div>

<!-- Futuristic Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-brain me-2"></i>NexLib<span style="color:#ff00ff">.space</span>
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

<!-- Hero Section Futuristik -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-crown"></i> Digital Library
            </h1>
            <p class="hero-subtitle">
                Jelajahi ribuan koleksi buku digital dengan pengalaman futuristik<br>
                <span class="stat-badge">✨ 1000+ Buku</span>
                <span class="stat-badge ms-2">🎯 Real-time Analytics</span>
            </p>
            
            <!-- Search Form -->
            <div class="search-box">
                <form method="GET" action="{{ route('katalog') }}">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <h5 class="sidebar-title">
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
                                <i class="fas fa-globe me-1"></i>Semua Kategori
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
                
                <hr class="my-4" style="border-color: rgba(0, 255, 255, 0.2);">
                
                <!-- Popular Books -->
                <h5 class="sidebar-title">
                    <i class="fas fa-fire me-2" style="color: #ff6600;"></i>🔥 Trending Now
                </h5>
                <div class="popular-books-list">
                    @forelse($popularBooks as $book)
                        <div class="popular-book-item" onclick="location.href='#book-{{ $book->id }}'">
                            @if($book->gambar)
                                <img src="{{ Storage::url($book->gambar) }}" class="popular-book-img" alt="{{ $book->judul }}">
                            @else
                                <img src="https://via.placeholder.com/55x70?text=No+Image" class="popular-book-img" alt="No Image">
                            @endif
                            <div class="popular-book-info">
                                <div class="popular-book-title">{{ Str::limit($book->judul, 35) }}</div>
                                <div class="popular-book-author">{{ $book->penulis }}</div>
                                <small style="color: #00ffff;">
                                    <i class="fas fa-chart-line"></i> {{ $book->total_dipinjam ?? 0 }} kali dipinjam
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3" style="color: rgba(255,255,255,0.5);">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada data trending</p>
                        </div>
                    @endforelse
                </div>
                
                <hr class="my-4" style="border-color: rgba(0, 255, 255, 0.2);">
                
                <!-- Recommended Books (Buku Rekomendasi) -->
                <h5 class="sidebar-title">
                    <i class="fas fa-robot me-2" style="color: #00ffaa;"></i>🤖 AI Recommendation
                </h5>
                <div class="recommendation-list">
                    @php
                        // Ambil buku rekomendasi (buku dengan stok terbanyak atau random)
                        $recommendedBooks = $books->take(5);
                        if($recommendedBooks->count() < 5 && isset($popularBooks)) {
                            $recommendedBooks = $popularBooks->take(5);
                        }
                    @endphp
                    
                    @forelse($recommendedBooks as $book)
                        <div class="recommendation-item" onclick="location.href='#book-{{ $book->id }}'">
                            @if($book->gambar)
                                <img src="{{ Storage::url($book->gambar) }}" class="recommendation-img" alt="{{ $book->judul }}">
                            @else
                                <img src="https://via.placeholder.com/55x70?text=No+Image" class="recommendation-img" alt="No Image">
                            @endif
                            <div class="recommendation-info">
                                <div class="recommendation-title">{{ Str::limit($book->judul, 35) }}</div>
                                <div class="recommendation-author">{{ $book->penulis }}</div>
                                <small style="color: #ff00ff;">
                                    <i class="fas fa-star"></i> Rekomendasi untukmu
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3" style="color: rgba(255,255,255,0.5);">
                            <i class="fas fa-robot fa-2x mb-2"></i>
                            <p class="mb-0">Loading rekomendasi...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-futuristic-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(0,255,0,0.1); border: 1px solid #00ff00; border-radius: 15px; color: #00ff00;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-futuristic-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(255,0,0,0.1); border: 1px solid #ff0000; border-radius: 15px; color: #ff4444;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Result Info -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h4 style="color: #fff; font-weight: 700;">
                        <i class="fas fa-database me-2" style="color: #00ffff;"></i>Koleksi Buku
                    </h4>
                    @if(request('search'))
                        <p style="color: rgba(255,255,255,0.6);" class="mb-0">
                            <i class="fas fa-search"></i> Hasil pencarian: "{{ request('search') }}"
                        </p>
                    @endif
                </div>
                <div>
                    <span class="stat-badge">
                        <i class="fas fa-chart-simple"></i> {{ $books->total() }} Buku ditemukan
                    </span>
                </div>
            </div>
            
            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="row g-4">
                    @foreach($books as $book)
                        <div class="col-md-6 col-lg-4" id="book-{{ $book->id }}">
                            <div class="book-card">
                                <div style="position: relative; overflow: hidden;">
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
                                        <i class="fas fa-user-astronaut me-1"></i>{{ $book->penulis }}
                                    </p>
                                    <p class="book-publisher">
                                        <i class="fas fa-building me-1"></i>{{ $book->penerbit }} | {{ $book->tahun_terbit }}
                                    </p>
                                    
                                    @if($book->kategori)
                                        <span style="background: rgba(0, 255, 255, 0.2); padding: 3px 10px; border-radius: 15px; font-size: 0.7rem; color: #00ffff; display: inline-block; margin-bottom: 15px;">
                                            <i class="fas fa-tag"></i> {{ $book->kategori->nama }}
                                        </span>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small style="color: rgba(255,255,255,0.5);">
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
                                                <button class="btn btn-secondary btn-sm" disabled style="background: rgba(255,255,255,0.1);">
                                                    <i class="fas fa-times-circle"></i> Habis
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-pinjam btn-sm">
                                                <i class="fas fa-sign-in-alt"></i> Login
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
                <div class="text-center py-5" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border-radius: 20px; border: 1px solid rgba(0,255,255,0.2);">
                    <i class="fas fa-book-open fa-4x" style="color: rgba(255,255,255,0.3); margin-bottom: 20px;"></i>
                    <h4 style="color: rgba(255,255,255,0.6);">Buku tidak ditemukan</h4>
                    <p style="color: rgba(255,255,255,0.4);">Maaf, buku yang Anda cari tidak tersedia di perpustakaan kami.</p>
                    <a href="{{ route('katalog') }}" class="btn btn-pinjam">
                        <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pinjam Buku Futuristik -->
<div class="modal fade" id="pinjamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #00ffff;">
                    <i class="fas fa-hand-peace me-2"></i>Konfirmasi Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pinjaman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="color: rgba(255,255,255,0.8);">Apakah Anda yakin ingin meminjam buku:</p>
                    <h6 class="fw-bold" id="modalBookTitle" style="background: linear-gradient(135deg, #00ffff, #ff00ff); -webkit-background-clip: text; background-clip: text; color: transparent;"></h6>
                    <input type="hidden" name="buku_id" id="modalBookId">
                    <div style="background: rgba(0, 255, 255, 0.1); border: 1px solid #00ffff; border-radius: 15px; padding: 15px; margin-top: 20px;">
                        <small style="color: #00ffff;">
                            <i class="fas fa-info-circle"></i> 
                            Masa peminjaman adalah 7 hari. Harap kembalikan tepat waktu.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.1); border: none;">Batal</button>
                    <button type="submit" class="btn btn-pinjam">
                        <i class="fas fa-check"></i> Ya, Pinjam Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer Futuristik -->
<footer class="footer">
    <div class="container text-center">
        <p class="mb-0" style="color: rgba(255,255,255,0.6);">
            <i class="fas fa-copyright me-1"></i> 2024 NexLib.space | 
            <i class="fas fa-heart" style="color: #ff00ff;"></i> Powered by AI | 
            <i class="fas fa-brain"></i> Future Library System
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
    
    // Smooth scroll untuk rekomendasi
    document.querySelectorAll('.recommendation-item, .popular-book-item').forEach(item => {
        item.addEventListener('click', function() {
            const href = this.getAttribute('onclick');
            if(href && href.includes('location.href')) {
                const targetId = href.match(/#book-\d+/);
                if(targetId) {
                    const element = document.querySelector(targetId[0]);
                    if(element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        });
    });
</script>
</body>
</html>