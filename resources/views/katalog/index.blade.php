@extends('layouts.app')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE (USER CUSTOM: NEON PASTEL GLASS)
       ============================================================ */
    :root {
        --color-black: #fbf8cc; /* Background utama krem terang */
        --color-1: #ffcfd2;     /* Pink pastel */
        --color-2: #ffb703;     /* Kuning sunshine */
        --color-3: #ffafcc;     /* Hot Pink */
        --color-4: #8ecae6;     /* Biru Langit */
        --color-5: #b5179e;     /* Magenta / Ungu cerah */
        --glass: rgba(255, 255, 255, 0.65);
        --glass-border: rgba(255, 255, 255, 0.8);
        --text-main: #2b2d42;   /* Navy gelap agar kontras */
        --text-muted: #6c757d;  /* Abu-abu gelap */
    }

    /* Menyembunyikan elemen layout admin bawaan */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: var(--color-black) !important; min-height: 100vh; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--color-black);
        color: var(--text-main);
        overflow-x: hidden;
        scroll-behavior: smooth;
        position: relative;
    }

    /* ============================================================
       2. AMBIENT BACKGROUND GLOW (INTERAKTIF & AESTHETIC)
       =========================================================== */
    .ambient-glow-1, .ambient-glow-2, .ambient-glow-3 {
        position: fixed; border-radius: 50%; filter: blur(120px); z-index: 0; pointer-events: none; opacity: 0.5;
        animation: floatOrb 15s ease-in-out infinite alternate;
    }
    .ambient-glow-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: var(--color-1); }
    .ambient-glow-2 { bottom: -10%; right: -5%; width: 40vw; height: 40vw; background: var(--color-4); animation-delay: -5s; }
    .ambient-glow-3 { top: 40%; left: 40%; width: 30vw; height: 30vw; background: var(--color-3); filter: blur(150px); opacity: 0.3; animation-delay: -10s; }

    @keyframes floatOrb {
        0% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, 50px) scale(1.1); }
        100% { transform: translate(-20px, 20px) scale(0.9); }
    }

    /* ============================================================
       3. NAVIGASI (FROSTED GLASS)
       =========================================================== */
    .catalog-nav {
        position: fixed; top: 0; left: 0; width: 100%;
        padding: 15px 50px; display: flex; justify-content: space-between; align-items: center;
        background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border);
        z-index: 1000; box-shadow: 0 4px 30px rgba(0,0,0,0.03);
    }
    .brand-logo { font-family: 'Unbounded', sans-serif; font-size: 1.8rem; font-weight: 900; color: var(--text-main); text-decoration: none; letter-spacing: 1px; display: flex; align-items: center;}
    .brand-logo i { color: var(--color-5); transform: rotate(-10deg); transition: 0.3s; }
    .brand-logo:hover i { transform: rotate(10deg) scale(1.2); }
    .brand-logo span { background: linear-gradient(45deg, var(--color-3), var(--color-4)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    
    .nav-links a { 
        text-decoration: none; color: var(--text-muted); font-weight: 800; font-size: 0.95rem;
        margin-left: 35px; transition: 0.3s; position: relative;
        padding: 8px 16px; border-radius: 20px;
    }
    .nav-links a:hover { background: rgba(255, 255, 255, 0.9); color: var(--color-5); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .nav-links a.active { background: var(--color-5); color: #fff; box-shadow: 0 5px 15px rgba(181, 23, 158, 0.3); }

    /* ============================================================
       4. HERO SECTION (ANIMATED TEXT)
       =========================================================== */
    .hero-section {
        padding: 160px 20px 80px; text-align: center; position: relative; z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .animated-title {
        font-family: 'Unbounded', sans-serif; font-size: clamp(3rem, 6vw, 5rem); font-weight: 900; line-height: 1.2; margin-bottom: 15px;
        background: linear-gradient(to right, var(--color-4), var(--color-5), var(--color-3), var(--color-4)); background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: gradientText 5s linear infinite;
        display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap; text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
    }
    .animated-title img { animation: bounceIcon 2s infinite ease-in-out; }
    @keyframes gradientText { 0% { background-position: 0% center; } 100% { background-position: 200% center; } }
    @keyframes bounceIcon { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-15px) rotate(15deg); } }
    
    .hero-subtitle { 
        color: var(--text-main); font-size: 1.2rem; font-weight: 600; max-width: 650px; margin: 0 auto; 
        letter-spacing: 0.5px; background: rgba(255,255,255,0.6); padding: 10px 25px; border-radius: 50px;
        border: 1px dashed var(--color-4);
    }

    /* ============================================================
       5. SEARCH BAR (NEON GLOW)
       =========================================================== */
    .search-wrapper { max-width: 850px; margin: 0 auto 60px; position: relative; z-index: 10; padding: 0 20px; width: 100%; }
    .search-glass {
        background: var(--glass); backdrop-filter: blur(20px); border-radius: 50px; padding: 10px 12px;
        display: flex; gap: 10px; align-items: center; border: 2px solid #fff;
        transition: 0.4s ease; box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    .search-glass:focus-within { border-color: var(--color-3); box-shadow: 0 0 30px rgba(255, 175, 204, 0.6); transform: translateY(-5px); }
    
    .search-glass input, .search-glass select {
        border: none; background: transparent; padding: 15px 20px; font-size: 1.05rem; color: var(--text-main);
        font-family: 'Plus Jakarta Sans'; font-weight: 700; outline: none; width: 100%;
    }
    .search-glass input::placeholder { color: #999; font-weight: 500; }
    .search-glass input { flex: 2; }
    .search-glass select { flex: 1; border-left: 2px dashed var(--color-1); color: var(--text-main); cursor: pointer; }
    .search-glass select option { background: var(--color-black); color: var(--text-main); }
    
    .btn-search {
        background: linear-gradient(135deg, var(--color-3), var(--color-5)); color: #fff; border: none; padding: 15px 45px; border-radius: 40px;
        font-weight: 900; cursor: pointer; transition: 0.3s; font-family: 'Unbounded', sans-serif; letter-spacing: 1.5px; white-space: nowrap;
        box-shadow: 0 5px 15px rgba(181, 23, 158, 0.4);
    }
    .btn-search:hover { transform: scale(1.05) translateY(-2px); box-shadow: 0 10px 25px rgba(181, 23, 158, 0.6); }

    /* ============================================================
       6. SECTION TITLES
       =========================================================== */
    .section-container { padding: 0 50px 80px; max-width: 1400px; margin: 0 auto; position: relative; z-index: 10; }
    .section-title { 
        font-family: 'Unbounded', sans-serif; font-size: 2rem; font-weight: 900; color: var(--text-main); 
        margin-bottom: 35px; display: flex; align-items: center; gap: 15px; letter-spacing: 0.5px;
        text-shadow: 2px 2px 0px #fff;
    }

    /* ============================================================
       7. TOP 10 REKOMENDASI (RAMAH ANAK SD)
       =========================================================== */
    .recommendation-bg {
        background: rgba(255,255,255,0.4); backdrop-filter: blur(20px);
        border-radius: 40px; padding: 40px; border: 2px solid #fff; margin-bottom: 70px;
        position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.04);
    }

    .slider-container { display: flex; gap: 30px; overflow-x: auto; padding: 25px 10px; scroll-behavior: smooth; scrollbar-width: none; }
    .slider-container::-webkit-scrollbar { display: none; }

    .book-card-top {
        min-width: 220px; max-width: 220px; background: rgba(255, 255, 255, 0.9); border-radius: 28px; padding: 18px;
        border: 2px solid #fff; position: relative; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .book-card-top:hover { 
        transform: translateY(-15px) rotate(-2deg); border-color: var(--color-2); 
        box-shadow: 0 15px 35px rgba(255, 183, 3, 0.4); 
    }

    .rank-badge {
        position: absolute; top: -15px; left: -15px; background: linear-gradient(135deg, var(--color-2), #ff9f1c); color: #fff;
        width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-family: 'Unbounded'; font-weight: 900; font-size: 1.3rem; z-index: 2; box-shadow: 0 5px 15px rgba(255, 183, 3, 0.5);
        border: 4px solid #fff; transform: rotate(-10deg);
    }
    
    .borrow-badge {
        position: absolute; top: 12px; right: 12px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(5px);
        color: var(--color-5); padding: 8px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 900; z-index: 2; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px dashed var(--color-3);
    }

    .book-cover { width: 100%; height: 280px; object-fit: cover; border-radius: 18px; margin-bottom: 18px; background: #f0f0f0; transition: 0.5s; border: 1px solid var(--glass-border); }
    .book-card-top:hover .book-cover { transform: scale(1.05); }
    .book-title { font-family: 'Unbounded', sans-serif; font-size: 1.05rem; font-weight: 800; line-height: 1.3; color: var(--text-main); margin-bottom: 8px; transition: 0.3s;}
    .book-card-top:hover .book-title { color: var(--color-5); }
    
    .book-author { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; }
    .book-author span { color: var(--color-4); font-weight: 800; }

    /* ============================================================
       8. KOLEKSI BUKU (GLASS GRID & RAMAH ANAK SD)
       =========================================================== */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 35px; transition: opacity 0.3s; }

    .book-card {
        background: rgba(255,255,255,0.7); border-radius: 28px; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid #fff; display: flex; flex-direction: column; height: 100%;
        backdrop-filter: blur(15px); box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .book-card:hover { 
        transform: translateY(-10px); border-color: var(--color-3); 
        box-shadow: 0 20px 40px rgba(255, 175, 204, 0.4); 
    }

    .card-img-wrapper { position: relative; height: 320px; width: 100%; overflow: hidden; background: #f0f0f0; border-bottom: 2px dashed #fff; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .book-card:hover .card-img-wrapper img { transform: scale(1.1) rotate(2deg); }

    .cat-pill {
        position: absolute; bottom: 12px; left: 12px; background: linear-gradient(135deg, var(--color-4), #48cae4); color: #fff;
        padding: 8px 16px; border-radius: 25px; font-size: 0.75rem; font-weight: 900; letter-spacing: 0.5px; z-index: 2; border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card-info { padding: 22px; display: flex; flex-direction: column; flex-grow: 1; }
    .c-title { font-family: 'Unbounded', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 12px; line-height: 1.4; transition: 0.3s; }
    .book-card:hover .c-title { color: var(--color-5); }
    
    .c-details-wrapper { background: rgba(255,255,255,0.8); padding: 15px; border-radius: 16px; margin-top: auto; border: 1px solid #fff; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);}
    
    .c-author-sd { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;}
    .c-author-sd strong { color: var(--color-5); font-weight: 800;}
    
    .c-year-sd { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px; font-weight: 600;}
    .c-year-sd strong { color: var(--color-2); font-weight: 800;}

    .c-stock-wrapper { display: flex; align-items: center; justify-content: center; padding: 10px; border-radius: 14px; font-weight: 900; font-size: 0.9rem; text-align: center; }
    .stock-in { background: #e0fbfc; color: #0277bd; border: 2px dashed #90e0ef; }
    .stock-out { background: #ffe5ec; color: #c1121f; border: 2px dashed #ffb3c6; }

    /* Navigasi Slider */
    .slider-nav-btn {
        position: absolute; top: 50%; transform: translateY(-50%); width: 55px; height: 55px; border-radius: 50%;
        background: #fff; color: var(--color-5); border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 10; display: flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 1.5rem; cursor: pointer;
    }
    .slider-nav-btn:hover { background: var(--color-3); color: #fff; transform: translateY(-50%) scale(1.15); box-shadow: 0 10px 25px rgba(255, 175, 204, 0.6); }
    .btn-prev { left: -25px; }
    .btn-next { right: -25px; }
    .slider-wrapper-rel { position: relative; }

    /* Float Button */
    .btn-float-back {
        position: fixed; bottom: 30px; right: 30px; background: linear-gradient(135deg, var(--color-5), var(--color-3)); color: #fff;
        padding: 15px 30px; border-radius: 50px; text-decoration: none; font-weight: 900; font-family: 'Unbounded'; border: 2px solid #fff;
        box-shadow: 0 10px 30px rgba(181, 23, 158, 0.4); transition: 0.4s; display: flex; align-items: center; gap: 12px; z-index: 1000;
    }
    .btn-float-back:hover { transform: translateY(-8px) scale(1.05); color: #fff; box-shadow: 0 15px 35px rgba(181, 23, 158, 0.6); }

    /* Pagination */
    .pagination { justify-content: center; margin-top: 60px; gap: 10px; }
    .page-item .page-link { background: rgba(255,255,255,0.8); border: 2px solid transparent; color: var(--text-muted); font-weight: 800; border-radius: 14px !important; padding: 12px 20px; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.05);}
    .page-item .page-link:hover { background: #fff; color: var(--color-5); border-color: var(--color-3); transform: translateY(-3px);}
    .page-item.active .page-link { background: var(--color-4); border-color: var(--color-4); color: #fff; box-shadow: 0 5px 20px rgba(142, 202, 230, 0.5); transform: scale(1.1);}

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 20px; }
        .hero-section { padding-top: 130px; }
        .animated-title { font-size: 2.2rem; }
        .search-glass { flex-direction: column; border-radius: 30px; padding: 15px; }
        .search-glass select { border-left: none; border-top: 2px dashed var(--color-1); margin-top: 10px; padding-top: 15px;}
        .btn-search { width: 100%; margin-top: 10px;}
        .section-container { padding: 0 20px 40px; }
        .recommendation-bg { padding: 30px 15px; border-radius: 30px;}
        .slider-nav-btn { display: none; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
        .card-img-wrapper { height: 250px; }
        .btn-float-back { bottom: 20px; right: 20px; padding: 12px 20px; font-size: 0.9rem; }
    }
</style>

<div class="ambient-glow-1"></div>
<div class="ambient-glow-2"></div>
<div class="ambient-glow-3"></div>

<nav class="catalog-nav">
    <a href="{{ url('/') }}" class="brand-logo"><i class="fas fa-book-reader me-2"></i>LIB<span>WE</span></a>
    <div class="nav-links d-none d-md-flex">
        <a href="{{ url('/') }}">Beranda</a>
        @if($popularBooks->count() > 0)
        <a href="#rekomendasi">⭐ Buku Favorit</a>
        @endif
        <a href="#koleksi" class="active">📚 Koleksi Buku</a>
    </div>
</nav>

<section class="hero-section">
    <h1 class="animated-title">
        KATALOG BUKU 
        <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/2728/512.gif" alt="Bintang" width="60" style="vertical-align: top; margin-top: -15px;">
    </h1>
    <p class="hero-subtitle">Ada banyak sekali cerita seru dan ilmu baru di Perpustakaan SDN Berat Wetan 1. Yuk cari bukunya di bawah ini!</p>
</section>

<div class="search-wrapper">
    <div class="search-glass">
        <i class="fas fa-search ms-3 d-none d-md-block" style="color: var(--color-5); font-size: 1.4rem;"></i>
        <input type="text" id="keyword" placeholder="Ketik judul buku yang mau kamu baca..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-search" onclick="filterBuku()"><i class="fas fa-magic me-2 d-md-none"></i>CARI</button>
    </div>
</div>

<div class="section-container">
    @if($popularBooks->count() > 0)
    <div class="recommendation-bg" id="rekomendasi">
        <h2 class="section-title">
            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f31f/512.gif" alt="Bintang" width="45" class="me-2"> 
            Paling Sering Dipinjam!
        </h2>
        <div class="slider-wrapper-rel">
            <button class="slider-nav-btn btn-prev" onclick="moveSlide(-280)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-nav-btn btn-next" onclick="moveSlide(280)"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-container" id="mainSlider">
                @foreach($popularBooks as $index => $pb)
                <div class="book-card-top">
                    <div class="rank-badge">#{{ $index + 1 }}</div>
                    
                    <div class="borrow-badge">⭐ {{ $pb->total_dipinjam ?? 0 }}x Dipinjam </div>
                    
                    @if($pb->gambar)
                        <img src="{{ asset($pb->gambar) }}" alt="{{ $pb->judul }}" class="book-cover">
                    @else
                        <div class="book-cover d-flex flex-column align-items-center justify-content-center" style="background: rgba(255,255,255,0.7);">
                            <i class="fas fa-book-open fa-4x mb-2" style="color: #ccc;"></i>
                        </div>
                    @endif
                    
                    <h3 class="book-title" title="{{ $pb->judul }}">{{ Str::limit($pb->judul, 32) }}</h3>
                    <p class="book-author">Oleh: <span>{{ Str::limit($pb->penulis ?? 'Anonim', 20) }}</span></p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div id="koleksi">
        <h2 class="section-title">
            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f4da/512.gif" alt="Buku" width="45" class="me-2"> 
            Jelajahi Semua Koleksi
        </h2>
        
        <div class="book-grid" id="containerKoleksi">
            @forelse($books as $b)
            <div class="book-card">
                <div class="card-img-wrapper">
                    <span class="cat-pill"><i class="fas fa-tag me-1"></i> {{ $b->kategori->nama ?? 'Umum' }}</span>
                    @if($b->gambar)
                        <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(255,255,255,0.7);">
                            <i class="fas fa-book-open fa-4x mb-2" style="color: #ccc;"></i>
                            <span style="color: #888; font-size: 0.9rem; font-weight: 800; font-family:'Unbounded';">FOTO MENYUSUL</span>
                        </div>
                    @endif
                </div>
                <div class="card-info">
                    <h3 class="c-title">{{ Str::limit($b->judul, 45) }}</h3>
                    
                    <div class="c-details-wrapper">
                        <div class="c-author-sd">
                            Penulis: <strong>{{ Str::limit($b->penulis ?? 'Anonim', 25) }}</strong>
                        </div>
                        <div class="c-year-sd">
                            Tahun Terbit: <strong>{{ $b->tahun_terbit ?? '-' }}</strong>
                        </div>
                        
                        @if($b->stok > 0)
                            <div class="c-stock-wrapper stock-in">
                                <i class="fas fa-check-circle me-2"></i> Sisa Stok : ({{ $b->stok }})
                            </div>
                        @else
                            <div class="c-stock-wrapper stock-out">
                                <i class="fas fa-clock me-2"></i> YAH, SEDANG DIPINJAM
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: rgba(255,255,255,0.7); border-radius: 40px; border: 3px dashed #fff; backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <h3 style="font-family: 'Unbounded'; color: var(--text-main); font-weight: 900; font-size: 2rem;">Yah, Bukunya Tidak Ketemu</h3>
                <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 600;">Coba ketik judul atau pilih kategori buku yang lain ya!</p>
            </div>
            @endforelse
        </div>

        @if($books->hasPages())
        <div class="d-flex justify-content-center">
            {{ $books->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

<a href="{{ url('/') }}" class="btn-float-back">
    <i class="fas fa-rocket"></i> Kembali Ke Beranda
</a>

<script>
    // Fitur geser slider Top 10
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        if(slider) slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // Fitur pencarian AJAX dengan efek loading smooth & neon glow
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');
        if(!container) return;
        
        // Animasi loading transisi
        container.style.opacity = '0.3';
        container.style.transform = 'translateY(20px) scale(0.98)';
        
        fetch(`{{ route('katalog.filter') }}?search=${encodeURIComponent(keyword)}&kategori=${encodeURIComponent(kategori)}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.success && data.books && data.books.length > 0) {
                    data.books.forEach(b => {
                        let hasCover = b.gambar ? true : false;
                        let imgPath = hasCover ? `/${b.gambar}` : ''; 
                        
                        let imgHtml = hasCover 
                            ? `<img src="${imgPath}" alt="${escapeHtml(b.judul)}">` 
                            : `<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(255,255,255,0.7);"><i class="fas fa-book-open fa-4x mb-2" style="color: #ccc;"></i><span style="color: #888; font-size: 0.9rem; font-weight: 800; font-family:'Unbounded';">FOTO MENYUSUL</span></div>`;
                        
                        let stockClass = b.stok > 0 ? 'stock-in' : 'stock-out';
                        let stockIcon = b.stok > 0 ? '<i class="fas fa-check-circle me-2"></i> BISA DIPINJAM (Stok: ' : '<i class="fas fa-clock me-2"></i> YAH, SEDANG DIPINJAM';
                        let stockText = b.stok > 0 ? b.stok + ')' : '';

                        html += `
                        <div class="book-card">
                            <div class="card-img-wrapper">
                                <span class="cat-pill"><i class="fas fa-tag me-1"></i> ${escapeHtml(b.kategori?.nama || 'Umum')}</span>
                                ${imgHtml}
                            </div>
                            <div class="card-info">
                                <h3 class="c-title">${escapeHtml(b.judul.length > 45 ? b.judul.substring(0,45) + '...' : b.judul)}</h3>
                                <div class="c-details-wrapper">
                                    <div class="c-author-sd">
                                        Penulis: <strong>${escapeHtml(b.penulis ? (b.penulis.length > 25 ? b.penulis.substring(0,25) + '...' : b.penulis) : 'Anonim')}</strong>
                                    </div>
                                    <div class="c-year-sd">
                                        Tahun Buku: <strong>${escapeHtml(b.tahun_terbit || '-')}</strong>
                                    </div>
                                    <div class="c-stock-wrapper ${stockClass}">
                                        ${stockIcon}${stockText}
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: rgba(255,255,255,0.7); border-radius: 40px; border: 3px dashed #fff; backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f50d/512.gif" alt="Cari" width="120" class="mb-4" style="opacity: 0.9;">
                        <h3 style="font-family: 'Unbounded'; color: var(--text-main); font-weight: 900; font-size: 2rem;">Yah, Bukunya Tidak Ketemu</h3>
                        <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 600;">Coba ketik judul atau pilih kategori buku yang lain ya!</p>
                    </div>`;
                }
                
                // Menunggu sedikit agar transisi interaktif terlihat
                setTimeout(() => {
                    container.innerHTML = html;
                    container.style.transform = 'translateY(0) scale(1)';
                    container.style.opacity = '1';
                }, 250);
                
                // Update URL Parameter diam-diam
                const url = new URL(window.location);
                if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error:', err);
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; background: rgba(255,255,255,0.9); border-radius: 30px;"><h3 style="color: var(--color-5); font-family: Unbounded;">Ups! Internet terputus. Coba lagi yuk!</h3></div>';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0) scale(1)';
            });
    }

    function escapeHtml(str) {
        if(!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if(m === '&') return '&amp;';
            if(m === '<') return '&lt;';
            if(m === '>') return '&gt;';
            return m;
        });
    }

    // Trigger pencarian saat tekan Enter
    document.getElementById('keyword')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') filterBuku();
    });
</script>
@endsection