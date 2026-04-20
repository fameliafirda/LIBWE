@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE (PREMIUM SOFT Y2K) & RESET
       ============================================================ */
    :root {
        --bg-main: #050508; 
        --bg-section: #0f0f16; /* Warna pembeda untuk section terpisah */
        --lavender: #d8b4e2;
        --soft-pink: #ffb3c6;
        --baby-blue: #9bf6ff;
        --glass: rgba(20, 20, 30, 0.5);
        --glass-border: rgba(255, 255, 255, 0.08);
        --text-muted: rgba(255, 255, 255, 0.5);
    }

    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: var(--bg-main) !important; 
        min-height: 100vh;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-main);
        color: #ffffff;
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Ambient Background Pattern */
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(216, 180, 226, 0.05) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(155, 246, 255, 0.05) 0%, transparent 40%);
        z-index: 0;
        pointer-events: none;
    }

    /* ============================================================
       2. NAVIGASI (SLEEK & MODERN)
       =========================================================== */
    .catalog-nav {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 20px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(5, 5, 8, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        z-index: 1000;
        border-bottom: 1px solid var(--glass-border);
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: 1px;
    }

    .brand-libwe span {
        color: var(--baby-blue);
    }

    .nav-links { display: flex; gap: 35px; align-items: center; }
    .nav-links a { 
        text-decoration: none; 
        color: var(--text-muted); 
        font-weight: 600; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--soft-pink); }

    /* ============================================================
       3. HERO PENCARIAN (MINIMALIST)
       =========================================================== */
    .hero-catalog {
        padding: 150px 20px 50px;
        text-align: center;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .hero-catalog h1 { 
        font-family: 'Unbounded', sans-serif; 
        font-size: clamp(2.5rem, 5vw, 4rem); 
        color: #fff; 
        margin-bottom: 5px; 
        letter-spacing: -1px;
    }

    .hero-catalog h1 span {
        background: linear-gradient(to right, var(--lavender), var(--soft-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-catalog p {
        color: var(--text-muted);
        font-weight: 500;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 40px;
        font-size: 0.9rem;
    }

    /* Kotak Pencarian Premium */
    .search-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        width: 100%;
        max-width: 850px;
        background: rgba(25, 25, 35, 0.4);
        padding: 12px;
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
    }

    .search-container input, .search-container select {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid transparent;
        padding: 16px 20px;
        border-radius: 12px;
        color: #fff;
        outline: none;
        flex: 1;
        font-weight: 500;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: 0.3s;
    }

    .search-container input:focus, .search-container select:focus {
        border-color: var(--lavender);
        background: rgba(0, 0, 0, 0.5);
    }

    .btn-cari {
        background: var(--lavender);
        color: var(--bg-main);
        border: none;
        padding: 0 40px;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        font-family: 'Unbounded', sans-serif;
        font-size: 0.9rem;
    }
    .btn-cari:hover { background: #fff; transform: translateY(-2px); }

    /* ============================================================
       4. SECTION REKOMENDASI (ISLAND LAYOUT - SANGAT BERBEDA)
       =========================================================== */
    .recommendation-wrapper {
        margin: 40px 40px 80px 40px;
        padding: 60px 0;
        background: linear-gradient(145deg, var(--bg-section), #0a0a0f);
        border-radius: 40px;
        border: 1px solid rgba(216, 180, 226, 0.1);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.02);
        position: relative;
        z-index: 10;
        overflow: hidden;
    }

    /* Ornamen background khusus rekomendasi */
    .recommendation-wrapper::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 179, 198, 0.05), transparent 50%);
        pointer-events: none;
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.8rem;
        color: #fff;
        position: relative;
    }

    .section-title i { color: var(--soft-pink); margin-right: 10px; }

    .slider-wrapper { position: relative; width: 100%; padding: 0 60px; }

    .track-container {
        display: flex; gap: 25px; overflow-x: auto;
        scroll-behavior: smooth; padding: 20px 10px;
        scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 180px; max-width: 180px;
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid var(--glass-border);
        padding: 15px; border-radius: 20px;
        transition: 0.4s; position: relative;
        display: flex; flex-direction: column;
    }

    .slider-card:hover { 
        transform: translateY(-8px); 
        border-color: var(--soft-pink); 
        background: rgba(20, 20, 30, 0.8);
    }

    .slider-card img {
        width: 100%; height: 240px; object-fit: cover;
        border-radius: 12px; margin-bottom: 15px; background: #050505;
    }

    .rank-badge {
        position: absolute; top: -10px; left: 15px;
        background: var(--soft-pink); color: var(--bg-main);
        padding: 6px 15px; border-radius: 10px;
        font-family: 'Unbounded'; font-size: 0.7rem; font-weight: 900;
        z-index: 2; box-shadow: 0 5px 15px rgba(255, 179, 198, 0.3);
    }

    .borrow-stats {
        position: absolute; top: 10px; right: 10px;
        background: rgba(5, 5, 8, 0.8); color: var(--baby-blue);
        backdrop-filter: blur(5px);
        padding: 5px 10px; border-radius: 8px;
        font-size: 0.7rem; font-weight: 700;
        border: 1px solid rgba(155, 246, 255, 0.2);
    }

    .book-title-small { font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
    .book-author-small { color: var(--text-muted); font-size: 0.75rem; font-weight: 600; margin-bottom: 0; }

    .btn-nav-slider {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 45px; height: 45px; border-radius: 50%;
        background: rgba(255,255,255,0.05); color: #fff;
        border: 1px solid rgba(255,255,255,0.1); cursor: pointer;
        z-index: 100; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(5px);
    }
    .btn-nav-slider:hover { background: #fff; color: var(--bg-main); }
    .btn-prev { left: 20px; }
    .btn-next { right: 20px; }

    /* ============================================================
       5. KOLEKSI BUKU (GRID UTAMA)
       =========================================================== */
    #koleksi { padding: 20px 60px 100px; position: relative; z-index: 10; }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 25px;
    }

    .book-item {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 12px;
        transition: 0.4s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .book-item:hover {
        border-color: var(--baby-blue);
        box-shadow: 0 10px 30px rgba(155, 246, 255, 0.1);
        transform: translateY(-5px);
    }

    .img-box {
        width: 100%; height: 230px; 
        background: #000; border-radius: 10px; overflow: hidden;
        position: relative; margin-bottom: 15px;
    }

    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.05); }

    .category-pill {
        position: absolute; top: 10px; right: 10px;
        padding: 4px 10px; background: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px); border-radius: 6px;
        font-size: 0.65rem; font-weight: 700;
        color: var(--lavender); border: 1px solid var(--lavender);
    }

    .book-info-container { display: flex; flex-direction: column; flex-grow: 1; }
    .b-title { 
        font-family: 'Unbounded'; font-size: 0.9rem; margin-bottom: 5px; 
        color: #fff; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .b-author { color: var(--text-muted); font-size: 0.75rem; margin-bottom: 12px; font-weight: 500; }
    
    .b-meta {
        margin-top: auto; display: flex; flex-direction: column; gap: 5px;
        font-size: 0.75rem; padding-top: 12px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .b-meta-row { display: flex; justify-content: space-between; align-items: center; }
    .b-year { color: var(--text-muted); }
    .b-stock { font-weight: 800; color: var(--baby-blue); }

    /* Floating Back Button */
    .btn-kembali {
        position: fixed; bottom: 30px; right: 30px; 
        background: #fff; color: var(--bg-main);
        padding: 12px 25px; border-radius: 50px; 
        text-decoration: none !important; font-weight: 800; z-index: 1000;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        transition: 0.3s; font-size: 0.85rem;
    }

    .btn-kembali:hover { transform: scale(1.05); background: var(--lavender); }

    /* ============================================================
       RESPONSIVE DESIGN
       =========================================================== */
    @media (max-width: 992px) {
        .catalog-nav { padding: 20px 30px; }
        .search-container { flex-direction: column; }
        .btn-cari { padding: 15px; }
        .recommendation-wrapper { margin: 20px 20px 60px 20px; border-radius: 30px; }
    }

    @media (max-width: 768px) {
        #koleksi, .slider-wrapper { padding-left: 20px; padding-right: 20px; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .img-box { height: 200px; }
        .slider-card { min-width: 150px; max-width: 150px; }
        .slider-card img { height: 210px; }
        .hero-catalog h1 { font-size: 2rem; }
        .btn-nav-slider { display: none; } 
        .recommendation-wrapper { margin: 10px 10px 40px 10px; padding: 40px 0; border-radius: 20px;}
    }
</style>

<nav class="catalog-nav">
    <div class="brand-libwe">LIB<span>WE</span></div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="#top10">Populer</a>
        <a href="#koleksi" class="active">Katalog</a>
    </div>
</nav>

<section class="hero-catalog">
    <h1>KATALOG <span>BUKU</span></h1>
    <p>Eksplorasi Koleksi Perpustakaan</p>

    <div class="search-container">
        <input type="text" id="keyword" placeholder="Cari judul, penulis..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">
            <i class="fas fa-search"></i> CARI
        </button>
    </div>
</section>

<div class="recommendation-wrapper" id="top10">
    <div class="section-title"><i class="fas fa-fire"></i> BUKU TERPOPULER</div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider btn-prev" onclick="moveSlide(-200)"><i class="fas fa-chevron-left"></i></button>
        <button class="btn-nav-slider btn-next" onclick="moveSlide(200)"><i class="fas fa-chevron-right"></i></button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                <div class="borrow-stats"><i class="fas fa-book-reader"></i> {{ $pb->jumlah_dipinjam ?? 0 }}x</div>
                
                @php
                    $gambarPathPopuler = $pb->gambar ? str_replace('public/', '', $pb->gambar) : null;
                @endphp
                
                <div class="img-box" style="height: 240px; margin-bottom: 10px;">
                    @if($gambarPathPopuler)
                        <img src="{{ asset('storage/' . $gambarPathPopuler) }}" alt="{{ $pb->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                    @else
                        <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                    @endif
                </div>
                
                <h3 class="book-title-small">{{ Str::limit($pb->judul, 28) }}</h3>
                <p class="book-author-small">{{ $pb->penulis ?? 'Anonim' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">
        @foreach($books as $b)
        <div class="book-item">
            @php
                $gambarPath = $b->gambar ? str_replace('public/', '', $b->gambar) : null;
            @endphp
            
            <div class="img-box">
                <span class="category-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                @if($gambarPath)
                    <img src="{{ asset('storage/' . $gambarPath) }}" alt="{{ $b->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                @endif
            </div>
            
            <div class="book-info-container">
                <h3 class="b-title">{{ $b->judul }}</h3>
                <p class="b-author">{{ $b->penulis ?? 'Anonim' }}</p>
                
                <div class="b-meta">
                    <div class="b-meta-row">
                        <span class="b-year"><i class="fas fa-calendar-alt"></i> {{ $b->tahun_terbit ?? '-' }}</span>
                        <span class="b-stock">Stok: {{ $b->stok ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center" style="margin-top: 60px;">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>
</section>

<a href="{{ url('/') }}" class="btn-kembali">
    <i class="fas fa-arrow-left"></i> KEMBALI
</a>

<script>
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');

        container.style.opacity = '0.3';

        fetch(`{{ route('katalog.filter') }}?search=${keyword}&kategori=${kategori}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                
                if(data.books && data.books.length > 0) {
                    data.books.forEach(b => {
                        // Pembersihan path gambar dari DB di JavaScript
                        let cleanPath = b.gambar ? b.gambar.replace('public/', '') : null;
                        let img = cleanPath ? `/storage/${cleanPath}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                        
                        let kname = b.kategori ? b.kategori.nama : 'Umum';
                        let tahun = b.tahun_terbit ? b.tahun_terbit : '-';
                        let stok = b.stok !== null ? b.stok : '0';
                        let penulis = b.penulis ? b.penulis : 'Anonim';
                        let judul = b.judul.length > 40 ? b.judul.substring(0,40) + '...' : b.judul;

                        html += `
                        <div class="book-item">
                            <div class="img-box">
                                <span class="category-pill">${kname}</span>
                                <img src="${img}" onerror="this.src='{{ asset("web-perpus/img/bukubaru.png") }}'">
                            </div>
                            <div class="book-info-container">
                                <h3 class="b-title">${judul}</h3>
                                <p class="b-author">${penulis}</p>
                                <div class="b-meta">
                                    <div class="b-meta-row">
                                        <span class="b-year"><i class="fas fa-calendar-alt"></i> ${tahun}</span>
                                        <span class="b-stock">Stok: ${stok}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div style="grid-column: 1/-1; text-align: center; padding: 60px; color: var(--text-muted);"><h3><i class="fas fa-box-open" style="font-size:3rem; margin-bottom:15px; display:block;"></i> Buku tidak ditemukan.</h3></div>';
                }

                container.innerHTML = html;
                container.style.opacity = '1';
                
                // Update URL Parameter
                const url = new URL(window.location);
                if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error:', err);
                container.style.opacity = '1';
            });
    }

    document.getElementById('keyword').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterBuku();
        }
    });
</script>
@endsection