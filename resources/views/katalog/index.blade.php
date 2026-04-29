@extends('layouts.app')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE (PREMIUM SLATE DARK THEME)
       ============================================================ */
    :root {
        --bg-main: #0f172a; /* Slate 900 - Tidak terlalu hitam */
        --bg-card: rgba(30, 41, 59, 0.7); /* Slate 800 with transparency */
        --bg-card-hover: rgba(30, 41, 59, 0.95);
        --border-glass: rgba(255, 255, 255, 0.08);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --accent-1: #6366f1; /* Indigo */
        --accent-2: #0ea5e9; /* Cyan */
        --accent-glow: rgba(99, 102, 241, 0.4);
    }

    /* Reset & Base */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: var(--bg-main) !important; min-height: 100vh; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-main);
        color: var(--text-main);
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Ambient Background Glow */
    body::before {
        content: ''; position: fixed; top: -20%; left: -10%; width: 50vw; height: 50vw;
        background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 60%);
        z-index: 0; pointer-events: none;
    }
    body::after {
        content: ''; position: fixed; bottom: -20%; right: -10%; width: 50vw; height: 50vw;
        background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 60%);
        z-index: 0; pointer-events: none;
    }

    /* ============================================================
       2. NAVIGASI (FROSTED GLASS)
       =========================================================== */
    .catalog-nav {
        position: fixed; top: 0; left: 0; width: 100%;
        padding: 15px 50px; display: flex; justify-content: space-between; align-items: center;
        background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(15px); border-bottom: 1px solid var(--border-glass);
        z-index: 1000;
    }
    .brand-logo { font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--text-main); text-decoration: none; letter-spacing: 1px; }
    .brand-logo span { color: var(--accent-2); }
    .nav-links a { 
        text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.95rem;
        margin-left: 35px; transition: 0.3s; position: relative;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--text-main); }
    .nav-links a.active::after {
        content: ''; position: absolute; bottom: -5px; left: 0; width: 100%; height: 2px;
        background: var(--accent-2); border-radius: 2px;
    }

    /* ============================================================
       3. HERO SECTION (ANIMATED TEXT)
       =========================================================== */
    .hero-section {
        padding: 160px 20px 80px; text-align: center; position: relative; z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .animated-title {
        font-family: 'Outfit', sans-serif; font-size: clamp(3rem, 6vw, 4.5rem); font-weight: 800; line-height: 1.1; margin-bottom: 15px;
        background: linear-gradient(to right, #a5b4fc, #67e8f9, #a5b4fc); background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: gradientText 4s linear infinite;
    }
    @keyframes gradientText { 0% { background-position: 0% center; } 100% { background-position: 200% center; } }
    .hero-subtitle { color: var(--text-muted); font-size: 1.1rem; font-weight: 500; max-width: 600px; margin: 0 auto; letter-spacing: 0.5px; }

    /* ============================================================
       4. SEARCH BAR (INTERACTIVE GLOW)
       =========================================================== */
    .search-wrapper { max-width: 850px; margin: 0 auto 60px; position: relative; z-index: 10; padding: 0 20px; width: 100%; }
    .search-glass {
        background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); border-radius: 50px; padding: 8px 12px;
        display: flex; gap: 10px; align-items: center; border: 1px solid var(--border-glass);
        transition: 0.4s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .search-glass:focus-within { border-color: var(--accent-1); box-shadow: 0 0 25px var(--accent-glow); transform: translateY(-2px); }
    
    .search-glass input, .search-glass select {
        border: none; background: transparent; padding: 15px 20px; font-size: 1rem; color: var(--text-main);
        font-family: 'Plus Jakarta Sans'; font-weight: 500; outline: none; width: 100%;
    }
    .search-glass input::placeholder { color: #64748b; }
    .search-glass input { flex: 2; }
    .search-glass select { flex: 1; border-left: 1px solid var(--border-glass); color: var(--text-muted); }
    .search-glass select option { background: var(--bg-main); color: var(--text-main); }
    
    .btn-search {
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2)); color: white; border: none; padding: 15px 40px; border-radius: 40px;
        font-weight: 700; cursor: pointer; transition: 0.3s; font-family: 'Outfit', sans-serif; letter-spacing: 1px; white-space: nowrap;
    }
    .btn-search:hover { transform: scale(1.05); box-shadow: 0 5px 15px var(--accent-glow); }

    /* ============================================================
       5. SECTION TITLES
       =========================================================== */
    .section-container { padding: 0 50px 80px; max-width: 1400px; margin: 0 auto; position: relative; z-index: 10; }
    .section-title { 
        font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 700; color: var(--text-main); 
        margin-bottom: 30px; display: flex; align-items: center; gap: 12px; letter-spacing: 0.5px;
    }
    .section-title i { color: var(--accent-2); }

    /* ============================================================
       6. TOP 10 REKOMENDASI (GLOWING CARDS)
       =========================================================== */
    .recommendation-bg {
        background: linear-gradient(to right bottom, rgba(30,41,59,0.5), rgba(15,23,42,0.5)); backdrop-filter: blur(10px);
        border-radius: 30px; padding: 40px; border: 1px solid var(--border-glass); margin-bottom: 60px;
    }

    .slider-container { display: flex; gap: 25px; overflow-x: auto; padding: 20px 10px; scroll-behavior: smooth; scrollbar-width: none; }
    .slider-container::-webkit-scrollbar { display: none; }

    .book-card-top {
        min-width: 190px; max-width: 190px; background: var(--bg-card); border-radius: 20px; padding: 15px;
        border: 1px solid var(--border-glass); position: relative; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;
    }
    .book-card-top:hover { 
        transform: translateY(-12px) scale(1.02); background: var(--bg-card-hover);
        border-color: var(--accent-2); box-shadow: 0 15px 35px rgba(14, 165, 233, 0.2); 
    }

    .rank-badge {
        position: absolute; top: -12px; left: 15px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white;
        width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-family: 'Outfit'; font-weight: 800; font-size: 1rem; z-index: 2; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4);
    }
    .borrow-badge {
        position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px);
        color: var(--accent-2); padding: 5px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 700; z-index: 2; border: 1px solid rgba(14,165,233,0.3);
    }

    .book-cover { width: 100%; height: 250px; object-fit: cover; border-radius: 12px; margin-bottom: 15px; background: #1e293b; transition: 0.4s; }
    .book-title { font-family: 'Outfit', sans-serif; font-size: 0.95rem; font-weight: 700; line-height: 1.3; color: var(--text-main); margin-bottom: 5px; transition: 0.3s;}
    .book-card-top:hover .book-title { color: var(--accent-2); }
    .book-author { color: var(--text-muted); font-size: 0.75rem; font-weight: 500; }

    /* ============================================================
       7. KOLEKSI BUKU (GLASS GRID)
       =========================================================== */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 30px; transition: opacity 0.3s; }

    .book-card {
        background: var(--bg-card); border-radius: 20px; overflow: hidden; transition: all 0.4s ease;
        border: 1px solid var(--border-glass); display: flex; flex-direction: column; height: 100%;
    }
    .book-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.2); background: var(--bg-card-hover); }

    .card-img-wrapper { position: relative; height: 280px; width: 100%; overflow: hidden; background: #1e293b; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .book-card:hover .card-img-wrapper img { transform: scale(1.08); filter: brightness(1.1); }

    .cat-pill {
        position: absolute; bottom: 10px; left: 10px; background: rgba(15,23,42,0.8); backdrop-filter: blur(4px); color: var(--accent-2);
        padding: 5px 12px; border-radius: 20px; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; z-index: 2; border: 1px solid rgba(14,165,233,0.2);
    }

    .card-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .c-title { font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; line-height: 1.4; transition: 0.3s; }
    .book-card:hover .c-title { color: var(--accent-1); }
    .c-author { color: var(--text-muted); font-size: 0.8rem; margin-bottom: 15px; }
    
    .c-footer { margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid var(--border-glass); }
    .c-year { color: var(--text-muted); font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }
    .c-stock { font-size: 0.75rem; font-weight: 800; padding: 6px 12px; border-radius: 8px; }
    .stock-in { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .stock-out { background: rgba(244, 63, 94, 0.1); color: #f43f5e; border: 1px solid rgba(244,63,94,0.2); }

    /* Navigasi Slider */
    .slider-nav-btn {
        position: absolute; top: 50%; transform: translateY(-50%); width: 45px; height: 45px; border-radius: 50%;
        background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(5px); color: var(--text-main); border: 1px solid var(--border-glass);
        z-index: 10; display: flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 1.2rem; cursor: pointer;
    }
    .slider-nav-btn:hover { background: var(--accent-1); border-color: var(--accent-1); transform: translateY(-50%) scale(1.1); }
    .btn-prev { left: 5px; }
    .btn-next { right: 5px; }
    .slider-wrapper-rel { position: relative; }

    /* Float Button */
    .btn-float-back {
        position: fixed; bottom: 30px; right: 30px; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(10px); color: var(--text-main);
        padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 700; font-family: 'Outfit'; border: 1px solid var(--border-glass);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5); transition: 0.3s; display: flex; align-items: center; gap: 10px; z-index: 1000;
    }
    .btn-float-back:hover { background: var(--accent-1); border-color: var(--accent-1); transform: translateY(-5px); color: white; }

    /* Pagination */
    .pagination { justify-content: center; margin-top: 60px; gap: 8px; }
    .page-item .page-link { background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--text-muted); font-weight: 600; border-radius: 12px !important; padding: 10px 18px; transition: 0.3s; }
    .page-item .page-link:hover { background: rgba(255,255,255,0.1); color: var(--text-main); }
    .page-item.active .page-link { background: var(--accent-1); border-color: var(--accent-1); color: white; box-shadow: 0 5px 15px var(--accent-glow); }

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 20px; }
        .hero-section { padding-top: 120px; }
        .animated-title { font-size: 2.2rem; }
        .search-glass { flex-direction: column; border-radius: 25px; padding: 15px; }
        .search-glass select { border-left: none; border-top: 1px solid var(--border-glass); }
        .btn-search { width: 100%; }
        .section-container { padding: 0 20px 40px; }
        .recommendation-bg { padding: 25px 15px; }
        .slider-nav-btn { display: none; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
        .card-img-wrapper { height: 220px; }
        .btn-float-back { bottom: 20px; right: 20px; padding: 10px 20px; font-size: 0.85rem; }
    }
</style>

<nav class="catalog-nav">
    <a href="{{ url('/') }}" class="brand-logo"><i class="fas fa-book-reader me-2"></i>LIB<span>WE</span></a>
    <div class="nav-links d-none d-md-flex">
        <a href="{{ url('/') }}">Beranda</a>
        @if($popularBooks->count() > 0)
        <a href="#rekomendasi">Top 10</a>
        @endif
        <a href="#koleksi" class="active">Koleksi Buku</a>
    </div>
</nav>

<section class="hero-section">
    <h1 class="animated-title">Eksplorasi Dunia Buku</h1>
    <p class="hero-subtitle">Temukan ribuan ilmu dan cerita menarik di Perpustakaan SDN Berat Wetan 1. Ketik judul yang kamu cari di bawah ini.</p>
</section>

<div class="search-wrapper">
    <div class="search-glass">
        <i class="fas fa-search ms-3 d-none d-md-block" style="color: var(--accent-2);"></i>
        <input type="text" id="keyword" placeholder="Cari judul buku atau nama penulis..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-search" onclick="filterBuku()"><i class="fas fa-search me-2 d-md-none"></i>CARI BUKU</button>
    </div>
</div>

<div class="section-container">
    @if($popularBooks->count() > 0)
    <div class="recommendation-bg" id="rekomendasi">
        <h2 class="section-title">
            <i class="fas fa-crown"></i> Buku Paling Favorit
        </h2>
        <div class="slider-wrapper-rel">
            <button class="slider-nav-btn btn-prev" onclick="moveSlide(-250)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-nav-btn btn-next" onclick="moveSlide(250)"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-container" id="mainSlider">
                @foreach($popularBooks as $index => $pb)
                <div class="book-card-top">
                    <div class="rank-badge">#{{ $index + 1 }}</div>
                    <div class="borrow-badge"><i class="fas fa-fire me-1" style="color: #f43f5e;"></i> {{ $pb->total_dipinjam ?? 0 }}x Dipinjam</div>
                    
                    @if($pb->gambar)
                        <img src="{{ asset($pb->gambar) }}" alt="{{ $pb->judul }}" class="book-cover">
                    @else
                        <div class="book-cover d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-book-open fa-3x mb-2" style="color: #334155;"></i>
                        </div>
                    @endif
                    
                    <h3 class="book-title" title="{{ $pb->judul }}">{{ Str::limit($pb->judul, 32) }}</h3>
                    <p class="book-author"><i class="fas fa-pen-nib me-1 opacity-75"></i> {{ Str::limit($pb->penulis ?? 'Anonim', 20) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div id="koleksi">
        <h2 class="section-title"><i class="fas fa-layer-group"></i> Semua Koleksi Buku</h2>
        
        <div class="book-grid" id="containerKoleksi">
            @forelse($books as $b)
            <div class="book-card">
                <div class="card-img-wrapper">
                    <span class="cat-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                    @if($b->gambar)
                        <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: #1e293b;">
                            <i class="fas fa-book-open fa-3x mb-2" style="color: #334155;"></i>
                            <span style="color: #475569; font-size: 0.8rem; font-weight: 600;">NO COVER</span>
                        </div>
                    @endif
                </div>
                <div class="card-info">
                    <h3 class="c-title">{{ Str::limit($b->judul, 45) }}</h3>
                    <p class="c-author"><i class="fas fa-user-edit me-1 opacity-50"></i> {{ Str::limit($b->penulis ?? 'Anonim', 25) }}</p>
                    
                    <div class="c-footer">
                        <span class="c-year"><i class="far fa-calendar-alt"></i> {{ $b->tahun_terbit ?? '-' }}</span>
                        @if($b->stok > 0)
                            <span class="c-stock stock-in"><i class="fas fa-check-circle me-1"></i> Tersedia: {{ $b->stok }}</span>
                        @else
                            <span class="c-stock stock-out"><i class="fas fa-times-circle me-1"></i> Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: var(--bg-card); border-radius: 30px; border: 1px dashed var(--border-glass);">
                <i class="fas fa-box-open fa-4x mb-3" style="color: #334155;"></i>
                <h3 style="font-family: 'Outfit'; color: var(--text-main);">Buku Tidak Ditemukan</h3>
                <p style="color: var(--text-muted);">Coba gunakan kata kunci atau kategori yang berbeda.</p>
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
    <i class="fas fa-sign-out-alt"></i> KEMBALI
</a>

<script>
    // Fitur geser slider Top 10
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        if(slider) slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // Fitur pencarian AJAX dengan efek loading smooth
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');
        if(!container) return;
        
        // Animasi loading transisi
        container.style.opacity = '0.2';
        container.style.transform = 'translateY(10px)';
        
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
                            : `<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: #1e293b;"><i class="fas fa-book-open fa-3x mb-2" style="color: #334155;"></i><span style="color: #475569; font-size: 0.8rem; font-weight: 600;">NO COVER</span></div>`;
                        
                        let stockClass = b.stok > 0 ? 'stock-in' : 'stock-out';
                        let stockIcon = b.stok > 0 ? '<i class="fas fa-check-circle me-1"></i> Tersedia: ' : '<i class="fas fa-times-circle me-1"></i> Habis';
                        let stockText = b.stok > 0 ? b.stok : '';

                        html += `
                        <div class="book-card">
                            <div class="card-img-wrapper">
                                <span class="cat-pill">${escapeHtml(b.kategori?.nama || 'Umum')}</span>
                                ${imgHtml}
                            </div>
                            <div class="card-info">
                                <h3 class="c-title">${escapeHtml(b.judul.length > 45 ? b.judul.substring(0,45) + '...' : b.judul)}</h3>
                                <p class="c-author"><i class="fas fa-user-edit me-1 opacity-50"></i> ${escapeHtml(b.penulis ? (b.penulis.length > 25 ? b.penulis.substring(0,25) + '...' : b.penulis) : 'Anonim')}</p>
                                <div class="c-footer">
                                    <span class="c-year"><i class="far fa-calendar-alt me-1"></i> ${escapeHtml(b.tahun_terbit || '-')}</span>
                                    <span class="c-stock ${stockClass}">${stockIcon}${stockText}</span>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; background: var(--bg-card); border-radius: 30px; border: 1px dashed var(--border-glass);">
                        <i class="fas fa-box-open fa-4x mb-3" style="color: #334155;"></i>
                        <h3 style="font-family: 'Outfit'; color: var(--text-main);">Buku Tidak Ditemukan</h3>
                        <p style="color: var(--text-muted);">Coba gunakan kata kunci atau kategori yang berbeda.</p>
                    </div>`;
                }
                
                // Menunggu sedikit agar transisi terlihat
                setTimeout(() => {
                    container.innerHTML = html;
                    container.style.transform = 'translateY(0)';
                    container.style.opacity = '1';
                }, 150);
                
                // Update URL Parameter diam-diam
                const url = new URL(window.location);
                if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error:', err);
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px;"><h3 style="color: #f43f5e;">Terjadi kesalahan koneksi</h3></div>';
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