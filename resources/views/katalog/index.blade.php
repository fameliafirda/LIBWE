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
        --color-black: #0a0a0a;
        --color-1: #cdb4db; /* Lilac */
        --color-2: #ffc8dd; /* Light Pink */
        --color-3: #ffafcc; /* Hot Pink */
        --color-4: #bde0fe; /* Cyan/Blue */
        --color-5: #a2d2ff; /* Deep Sky Blue */
        --glass: rgba(20, 20, 20, 0.45);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-main: #ffffff;
        --text-muted: #a0a0a0;
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
        position: fixed; border-radius: 50%; filter: blur(120px); z-index: 0; pointer-events: none; opacity: 0.4;
        animation: floatOrb 15s ease-in-out infinite alternate;
    }
    .ambient-glow-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: var(--color-1); }
    .ambient-glow-2 { bottom: -10%; right: -5%; width: 40vw; height: 40vw; background: var(--color-4); animation-delay: -5s; }
    .ambient-glow-3 { top: 40%; left: 40%; width: 30vw; height: 30vw; background: var(--color-3); filter: blur(150px); opacity: 0.2; animation-delay: -10s; }

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
        background: rgba(10, 10, 10, 0.7); backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border);
        z-index: 1000;
    }
    .brand-logo { font-family: 'Unbounded', sans-serif; font-size: 1.8rem; font-weight: 900; color: var(--text-main); text-decoration: none; letter-spacing: 1px; }
    .brand-logo span { background: linear-gradient(45deg, var(--color-3), var(--color-4)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .nav-links a { 
        text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.95rem;
        margin-left: 35px; transition: 0.3s; position: relative;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--color-2); }
    .nav-links a.active::after {
        content: ''; position: absolute; bottom: -5px; left: 0; width: 100%; height: 2px;
        background: var(--color-3); border-radius: 2px; box-shadow: 0 0 10px var(--color-3);
    }

    /* ============================================================
       4. HERO SECTION (ANIMATED TEXT)
       =========================================================== */
    .hero-section {
        padding: 160px 20px 80px; text-align: center; position: relative; z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .animated-title {
        font-family: 'Unbounded', sans-serif; font-size: clamp(3rem, 6vw, 4.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 15px;
        background: linear-gradient(to right, var(--color-4), var(--color-1), var(--color-3), var(--color-4)); background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: gradientText 5s linear infinite;
        display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;
    }
    @keyframes gradientText { 0% { background-position: 0% center; } 100% { background-position: 200% center; } }
    .hero-subtitle { color: var(--text-muted); font-size: 1.1rem; font-weight: 500; max-width: 600px; margin: 0 auto; letter-spacing: 0.5px; }

    /* ============================================================
       5. SEARCH BAR (NEON GLOW)
       =========================================================== */
    .search-wrapper { max-width: 850px; margin: 0 auto 60px; position: relative; z-index: 10; padding: 0 20px; width: 100%; }
    .search-glass {
        background: var(--glass); backdrop-filter: blur(15px); border-radius: 50px; padding: 8px 12px;
        display: flex; gap: 10px; align-items: center; border: 1px solid var(--glass-border);
        transition: 0.4s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .search-glass:focus-within { border-color: var(--color-4); box-shadow: 0 0 25px rgba(189, 224, 254, 0.2); transform: translateY(-2px); }
    
    .search-glass input, .search-glass select {
        border: none; background: transparent; padding: 15px 20px; font-size: 1rem; color: var(--text-main);
        font-family: 'Plus Jakarta Sans'; font-weight: 500; outline: none; width: 100%;
    }
    .search-glass input::placeholder { color: #555; }
    .search-glass input { flex: 2; }
    .search-glass select { flex: 1; border-left: 1px solid var(--glass-border); color: var(--text-main); cursor: pointer; }
    .search-glass select option { background: var(--color-black); color: var(--text-main); }
    
    .btn-search {
        background: linear-gradient(135deg, var(--color-4), var(--color-5)); color: var(--color-black); border: none; padding: 15px 40px; border-radius: 40px;
        font-weight: 800; cursor: pointer; transition: 0.3s; font-family: 'Unbounded', sans-serif; letter-spacing: 1px; white-space: nowrap;
    }
    .btn-search:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(162, 210, 255, 0.5); background: linear-gradient(135deg, var(--color-5), var(--color-4)); }

    /* ============================================================
       6. SECTION TITLES
       =========================================================== */
    .section-container { padding: 0 50px 80px; max-width: 1400px; margin: 0 auto; position: relative; z-index: 10; }
    .section-title { 
        font-family: 'Unbounded', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--text-main); 
        margin-bottom: 30px; display: flex; align-items: center; gap: 12px; letter-spacing: 0.5px;
    }
    .section-title i { background: linear-gradient(45deg, var(--color-3), var(--color-2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* ============================================================
       7. TOP 10 REKOMENDASI (RAMAH ANAK SD)
       =========================================================== */
    .recommendation-bg {
        background: var(--glass); backdrop-filter: blur(15px);
        border-radius: 30px; padding: 40px; border: 1px solid var(--glass-border); margin-bottom: 60px;
        position: relative; overflow: hidden;
    }

    .slider-container { display: flex; gap: 25px; overflow-x: auto; padding: 20px 10px; scroll-behavior: smooth; scrollbar-width: none; }
    .slider-container::-webkit-scrollbar { display: none; }

    .book-card-top {
        min-width: 200px; max-width: 200px; background: rgba(10, 10, 10, 0.6); border-radius: 24px; padding: 15px;
        border: 1px solid var(--glass-border); position: relative; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;
    }
    .book-card-top:hover { 
        transform: translateY(-12px); border-color: var(--color-3); 
        box-shadow: 0 10px 30px rgba(255, 175, 204, 0.2); 
    }

    .rank-badge {
        position: absolute; top: -15px; left: -10px; background: var(--color-3); color: var(--color-black);
        width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-family: 'Unbounded'; font-weight: 900; font-size: 1.1rem; z-index: 2; box-shadow: 0 4px 15px rgba(255, 175, 204, 0.5);
        border: 3px solid var(--color-black);
    }
    
    /* PERBAIKAN TAMPILAN DIPINJAM UNTUK ANAK SD */
    .borrow-badge {
        position: absolute; top: 10px; right: 10px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px);
        color: #ff3f81; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; z-index: 2; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.3); border: 2px solid #ffafcc;
    }

    .book-cover { width: 100%; height: 260px; object-fit: cover; border-radius: 16px; margin-bottom: 15px; background: #111; transition: 0.4s; border: 1px solid rgba(255,255,255,0.05); }
    .book-card-top:hover .book-cover { transform: scale(1.03); }
    .book-title { font-family: 'Unbounded', sans-serif; font-size: 0.95rem; font-weight: 700; line-height: 1.3; color: var(--text-main); margin-bottom: 5px; transition: 0.3s;}
    .book-card-top:hover .book-title { color: var(--color-2); }
    
    /* PERBAIKAN TAMPILAN PENULIS */
    .book-author { color: var(--text-muted); font-size: 0.8rem; font-weight: 500; }
    .book-author span { color: var(--color-1); font-weight: 700; }

    /* ============================================================
       8. KOLEKSI BUKU (GLASS GRID & RAMAH ANAK SD)
       =========================================================== */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; transition: opacity 0.3s; }

    .book-card {
        background: var(--glass); border-radius: 24px; overflow: hidden; transition: all 0.4s ease;
        border: 1px solid var(--glass-border); display: flex; flex-direction: column; height: 100%;
        backdrop-filter: blur(10px);
    }
    .book-card:hover { 
        transform: translateY(-8px); border-color: var(--color-4); 
        box-shadow: 0 15px 35px rgba(189, 224, 254, 0.15); 
    }

    .card-img-wrapper { position: relative; height: 300px; width: 100%; overflow: hidden; background: #111; border-bottom: 1px solid var(--glass-border); filter: brightness(0.95); }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .book-card:hover .card-img-wrapper { filter: brightness(1.1); }
    .book-card:hover .card-img-wrapper img { transform: scale(1.08); }

    .cat-pill {
        position: absolute; bottom: 10px; left: 10px; background: rgba(10,10,10,0.85); backdrop-filter: blur(4px); color: var(--color-5);
        padding: 6px 14px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px; z-index: 2; border: 1px solid rgba(162,210,255,0.3);
    }

    .card-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .c-title { font-family: 'Unbounded', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; line-height: 1.4; transition: 0.3s; }
    .book-card:hover .c-title { color: var(--color-4); }
    
    /* PERBAIKAN DETAIL UNTUK ANAK SD */
    .c-details-wrapper { background: rgba(255,255,255,0.05); padding: 12px; border-radius: 12px; margin-top: auto; }
    
    .c-author-sd { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 500;}
    .c-author-sd strong { color: var(--color-2); }
    
    .c-year-sd { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px; font-weight: 500;}
    .c-year-sd strong { color: var(--color-1); }

    .c-stock-wrapper { display: flex; align-items: center; justify-content: center; padding: 8px; border-radius: 12px; font-weight: 800; font-size: 0.85rem; }
    .stock-in { background: rgba(189, 224, 254, 0.2); color: var(--color-4); border: 1px solid rgba(189,224,254,0.4); }
    .stock-out { background: rgba(255, 175, 204, 0.2); color: var(--color-3); border: 1px solid rgba(255,175,204,0.4); }

    /* Navigasi Slider */
    .slider-nav-btn {
        position: absolute; top: 50%; transform: translateY(-50%); width: 45px; height: 45px; border-radius: 50%;
        background: rgba(10, 10, 10, 0.8); backdrop-filter: blur(5px); color: var(--color-2); border: 1px solid var(--glass-border);
        z-index: 10; display: flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 1.2rem; cursor: pointer;
    }
    .slider-nav-btn:hover { background: var(--color-3); border-color: var(--color-3); color: var(--color-black); transform: translateY(-50%) scale(1.1); box-shadow: 0 0 15px var(--color-3); }
    .btn-prev { left: -15px; }
    .btn-next { right: -15px; }
    .slider-wrapper-rel { position: relative; }

    /* Float Button */
    .btn-float-back {
        position: fixed; bottom: 30px; right: 30px; background: var(--glass); backdrop-filter: blur(10px); color: var(--text-main);
        padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 800; font-family: 'Unbounded'; border: 1px solid var(--glass-border);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5); transition: 0.3s; display: flex; align-items: center; gap: 10px; z-index: 1000;
    }
    .btn-float-back:hover { background: var(--color-1); border-color: var(--color-1); transform: translateY(-5px); color: var(--color-black); box-shadow: 0 0 20px rgba(205,180,219,0.5); }

    /* Pagination */
    .pagination { justify-content: center; margin-top: 60px; gap: 8px; }
    .page-item .page-link { background: var(--glass); border: 1px solid var(--glass-border); color: var(--text-muted); font-weight: 700; border-radius: 12px !important; padding: 10px 18px; transition: 0.3s; }
    .page-item .page-link:hover { background: rgba(255,255,255,0.1); color: var(--color-4); border-color: var(--color-4); }
    .page-item.active .page-link { background: var(--color-4); border-color: var(--color-4); color: var(--color-black); box-shadow: 0 0 15px rgba(189,224,254,0.4); }

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 20px; }
        .hero-section { padding-top: 120px; }
        .animated-title { font-size: 2rem; }
        .search-glass { flex-direction: column; border-radius: 25px; padding: 15px; }
        .search-glass select { border-left: none; border-top: 1px solid var(--glass-border); }
        .btn-search { width: 100%; }
        .section-container { padding: 0 20px 40px; }
        .recommendation-bg { padding: 25px 15px; }
        .slider-nav-btn { display: none; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
        .card-img-wrapper { height: 220px; }
        .btn-float-back { bottom: 20px; right: 20px; padding: 10px 20px; font-size: 0.85rem; }
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
        <a href="#rekomendasi">Buku Favorit</a>
        @endif
        <a href="#koleksi" class="active">Koleksi Buku</a>
    </div>
</nav>

<section class="hero-section">
    <h1 class="animated-title">
        KATALOG BUKU 
        <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/2728/512.gif" alt="Bintang" width="50" style="vertical-align: top; margin-top: -10px;">
    </h1>
    <p class="hero-subtitle">Ada banyak sekali cerita seru dan ilmu baru di Perpustakaan SDN Berat Wetan 1. Yuk cari bukunya di bawah ini!</p>
</section>

<div class="search-wrapper">
    <div class="search-glass">
        <i class="fas fa-search ms-3 d-none d-md-block" style="color: var(--color-1); font-size: 1.2rem;"></i>
        <input type="text" id="keyword" placeholder="Ketik judul buku yang mau kamu baca..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Kategori Buku</option>
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
            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f31f/512.gif" alt="Bintang" width="35" class="me-2"> 
            Rekomendasi Buku
        </h2>
        <div class="slider-wrapper-rel">
            <button class="slider-nav-btn btn-prev" onclick="moveSlide(-250)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-nav-btn btn-next" onclick="moveSlide(250)"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-container" id="mainSlider">
                @foreach($popularBooks as $index => $pb)
                <div class="book-card-top">
                    <div class="rank-badge">#{{ $index + 1 }}</div>
                    
                    <div class="borrow-badge">⭐ {{ $pb->total_dipinjam ?? 0 }}x Dipinjam </div>
                    
                    @if($pb->gambar)
                        <img src="{{ asset($pb->gambar) }}" alt="{{ $pb->judul }}" class="book-cover">
                    @else
                        <div class="book-cover d-flex flex-column align-items-center justify-content-center" style="background: rgba(20,20,20,0.5);">
                            <i class="fas fa-book-open fa-3x mb-2" style="color: #444;"></i>
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
            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f4da/512.gif" alt="Buku" width="35" class="me-2"> 
            Daftar Semua Buku
        </h2>
        
        <div class="book-grid" id="containerKoleksi">
            @forelse($books as $b)
            <div class="book-card">
                <div class="card-img-wrapper">
                    <span class="cat-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                    @if($b->gambar)
                        <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(20,20,20,0.5);">
                            <i class="fas fa-book-open fa-3x mb-2" style="color: #444;"></i>
                            <span style="color: #666; font-size: 0.8rem; font-weight: 700; font-family:'Unbounded';">BELUM ADA FOTO</span>
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
                            Tahun Buku: <strong>{{ $b->tahun_terbit ?? '-' }}</strong>
                        </div>
                        
                        @if($b->stok > 0)
                            <div class="c-stock-wrapper stock-in">
                                <i class="fas fa-check-circle me-2"></i> Stok Buku: {{ $b->stok }}
                            </div>
                        @else
                            <div class="c-stock-wrapper stock-out">
                                <i class="fas fa-times-circle me-2"></i> Wah, Bukunya Sedang Dipinjam
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: var(--glass); border-radius: 30px; border: 1px dashed var(--glass-border); backdrop-filter: blur(10px);">
                <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f50d/512.gif" alt="Cari" width="100" class="mb-3" style="opacity: 0.8;">
                <h3 style="font-family: 'Unbounded'; color: var(--text-main);">Yah, Bukunya Tidak Ketemu</h3>
                <p style="color: var(--text-muted);">Coba ketik nama buku yang lain ya!</p>
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
    <i class="fas fa-home"></i> Kembali ke Beranda
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
        container.style.opacity = '0.2';
        container.style.transform = 'translateY(15px)';
        
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
                            : `<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(20,20,20,0.5);"><i class="fas fa-book-open fa-3x mb-2" style="color: #444;"></i><span style="color: #666; font-size: 0.8rem; font-weight: 700; font-family:'Unbounded';">BELUM ADA FOTO</span></div>`;
                        
                        let stockClass = b.stok > 0 ? 'stock-in' : 'stock-out';
                        let stockIcon = b.stok > 0 ? '<i class="fas fa-check-circle me-2"></i> Bisa Dipinjam: ' : '<i class="fas fa-times-circle me-2"></i> Wah, Bukunya Sedang Dipinjam';
                        let stockText = b.stok > 0 ? b.stok + ' Buku' : '';

                        html += `
                        <div class="book-card">
                            <div class="card-img-wrapper">
                                <span class="cat-pill">${escapeHtml(b.kategori?.nama || 'Umum')}</span>
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
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: var(--glass); border-radius: 30px; border: 1px dashed var(--glass-border); backdrop-filter: blur(10px);">
                        <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f50d/512.gif" alt="Cari" width="100" class="mb-3" style="opacity: 0.8;">
                        <h3 style="font-family: 'Unbounded'; color: var(--text-main);">Yah, Bukunya Tidak Ketemu</h3>
                        <p style="color: var(--text-muted);">Coba ketik nama buku yang lain ya!</p>
                    </div>`;
                }
                
                // Menunggu sedikit agar transisi interaktif terlihat
                setTimeout(() => {
                    container.innerHTML = html;
                    container.style.transform = 'translateY(0)';
                    container.style.opacity = '1';
                }, 200);
                
                // Update URL Parameter diam-diam
                const url = new URL(window.location);
                if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error:', err);
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; background: var(--glass); border-radius: 20px;"><h3 style="color: var(--color-3);">Koneksi terputus, coba lagi yuk!</h3></div>';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
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