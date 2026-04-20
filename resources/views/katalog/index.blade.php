@extends('layouts.app')

@section('title', 'Katalog Digital SDN Berat Wetan 1')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Unbounded:wght@700&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       1. CORE THEME & LAYOUT (CYBER-Y2K DARK)
       ============================================================ */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: #02020a !important; 
        min-height: 100vh;
        overflow: hidden;
    }

    :root {
        --y2k-pink: #ff00ff;
        --y2k-purple: #bc13fe;
        --y2k-blue: #00f2ff;
        --y2k-yellow: #ffea00;
        --deep-space: #02020a;
        --glass: rgba(255, 255, 255, 0.05);
    }

    body {
        background: radial-gradient(circle at top right, #1a0b2e, #02020a);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        scroll-behavior: smooth;
    }

    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
        position: relative;
        z-index: 10;
    }

    /* BACKGROUND NEON DOTS ANIMATION */
    .nebula-bg {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: radial-gradient(var(--glass) 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: 1;
        opacity: 0.3;
        animation: nebulaFloat 30s linear infinite;
    }

    @keyframes nebulaFloat {
        0% { background-position: 0 0; }
        100% { background-position: 100px 100px; }
    }

    /* ============================================================
       2. FIXED TOP NAVIGATION (HUSH LAYOUT VIBE)
       =========================================================== */
    .y2k-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        padding: 15px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(2, 2, 10, 0.7);
        backdrop-filter: blur(25px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 1000;
        box-shadow: 0 5px 20px rgba(0,0,0,0.5);
    }

    .nav-left { display: flex; align-items: center; gap: 20px; }
    .logo-hush-y2k { font-family: 'Unbounded', sans-serif; font-size: 1.5rem; color: var(--y2k-pink); text-transform: uppercase; letter-spacing: -1px; filter: drop-shadow(0 0 5px var(--y2k-pink)); }
    .school-id { font-size: 0.7rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; }

    .nav-center-links { display: flex; gap: 30px; }
    .nav-center-links a { 
        text-decoration: none; 
        color: #fff; 
        font-weight: 600; 
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .nav-center-links a:hover { color: var(--y2k-blue); text-shadow: 0 0 10px var(--y2k-blue); }

    .nav-right-icons { color: var(--y2k-yellow); font-size: 1.2rem; cursor: pointer; }

    /* ============================================================
       3. HERO SECTION (BUKU UTAMA)
       =========================================================== */
    .hero-section {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .hero-text-bg {
        position: absolute;
        font-family: 'Unbounded', sans-serif;
        font-size: 18vw;
        color: rgba(255, 255, 255, 0.02);
        z-index: 1;
        white-space: nowrap;
        animation: textFloat 10s ease-in-out infinite;
    }

    @keyframes textFloat { 0%, 100% { transform: translateX(-10%); } 50% { transform: translateX(10%); } }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .hero-book-wrapper {
        position: relative;
        width: 380px;
        margin: 0 auto;
        transition: 0.5s;
    }

    .hero-book-wrapper:hover { transform: translateY(-15px) rotate(2deg); }

    .hero-book-wrapper img {
        width: 100%;
        border-radius: 12px;
        border: 2px solid var(--glass);
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    }

    /* Elemen Futuristik Kuning Melayang */
    .y2k-orb { position: absolute; width: 40px; height: 40px; background: var(--y2k-yellow); border-radius: 50%; box-shadow: 0 0 30px var(--y2k-yellow); animation: orbFloat 4s ease-in-out infinite; }
    @keyframes orbFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-30px); } }

    .hero-desc { margin-top: 30px; }
    .hero-desc h2 { font-family: 'Unbounded'; font-size: 2.5rem; margin-bottom: 5px; color: #fff; text-shadow: 0 0 10px rgba(255,255,255,0.5); }
    .hero-desc p { color: rgba(255,255,255,0.6); margin-bottom: 20px; }

    .btn-y2k {
        background: linear-gradient(45deg, var(--y2k-purple), var(--y2k-pink));
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 50px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        box-shadow: 0 0 15px var(--y2k-pink);
    }
    .btn-y2k:hover { transform: scale(1.05); box-shadow: 0 0 25px var(--y2k-pink); }

    /* ============================================================
       4. REKOMENDASI SECTION (PINK SLIDER)
       =========================================================== */
    #rekomendasi {
        background: linear-gradient(135deg, #1a0b2e, var(--y2k-pink));
        padding: 100px 0;
        border-top: 2px solid var(--y2k-purple);
        border-bottom: 2px solid var(--y2k-purple);
        position: relative;
    }

    .slider-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        margin-top: 50px;
    }

    .slider-track {
        display: flex;
        gap: 25px;
        overflow: hidden;
        width: 1000px;
        scroll-behavior: smooth;
        padding: 20px 0;
    }

    .slider-item {
        min-width: 280px;
        background: var(--glass);
        backdrop-filter: blur(15px);
        padding: 25px;
        border-radius: 25px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
        transition: 0.4s;
    }

    .slider-item:hover { border-color: var(--y2k-blue); box-shadow: 0 10px 30px rgba(0, 242, 255, 0.2); }

    .slider-item img {
        width: 180px;
        height: 260px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .card-tren-rank { position: absolute; bottom: 10px; right: 20px; font-size: 5rem; font-weight: 900; color: rgba(255,255,255,0.05); -webkit-text-stroke: 1px rgba(255,255,255,0.1); font-family: 'Unbounded'; }

    .nav-btn {
        background: transparent;
        border: 2px solid var(--y2k-blue);
        width: 55px; height: 55px;
        border-radius: 50%;
        color: var(--y2k-blue);
        cursor: pointer;
        font-size: 1.3rem;
        transition: 0.3s;
    }
    .nav-btn:hover { background: var(--y2k-blue); color: #fff; box-shadow: 0 0 15px var(--y2k-blue); }

    /* ============================================================
       5. KATALOG SECTION (GRID)
       =========================================================== */
    #katalog {
        padding: 100px 0;
    }

    .section-title { margin-bottom: 60px; text-align: center; }
    .section-title h2 { font-family: 'Unbounded'; font-size: 2rem; color: #fff; }
    .section-title h2 span { color: var(--y2k-yellow); text-shadow: 0 0 10px var(--y2k-yellow); }

    .search-hush-cyber {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 60px;
    }

    .input-cyber {
        width: 450px;
        padding: 15px 25px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: var(--glass);
        color: #fff;
        outline: none;
        transition: 0.3s;
    }
    .input-cyber:focus { border-color: var(--y2k-pink); box-shadow: 0 0 15px var(--y2k-pink); }

    .select-cyber { width: 220px; cursor: pointer; color: rgba(255,255,255,0.7); }

    .katalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 35px;
    }

    .book-node {
        background: var(--glass);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 15px;
        transition: 0.4s;
        text-align: center;
    }

    .book-node:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--y2k-purple);
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(188, 19, 254, 0.2);
    }

    .node-img {
        width: 100%;
        height: 280px;
        border-radius: 15px;
        margin-bottom: 15px;
    }

    .node-title { font-weight: 700; font-size: 1rem; color: #fff; margin-bottom: 3px; }
    .node-author { font-size: 0.8rem; color: #aaa; margin-bottom: 10px; }

    .tag-category {
        display: inline-block;
        padding: 3px 12px;
        background: rgba(0, 242, 255, 0.1);
        color: var(--y2k-blue);
        border-radius: 20px;
        font-size: 9px;
        text-transform: uppercase;
        border: 1px solid var(--y2k-blue);
        font-weight: bold;
    }

    /* Floating Exit Button */
    .btn-floating-exit { position: fixed; bottom: 30px; left: 30px; background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); color: #fff; padding: 15px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; z-index: 1000; border: 1px solid rgba(255, 255, 255, 0.1); transition: 0.3s; }
    .btn-floating-exit:hover { background: var(--y2k-purple); border-color: transparent; box-shadow: 0 0 20px var(--y2k-purple); }

</style>

<div class="nebula-bg"></div>

<a href="{{ url('/') }}" class="btn-floating-exit">
    <i class="fas fa-power-off me-2"></i> KELUAR KE HOME
</a>

<nav class="y2k-nav">
    <div class="nav-left">
        <div class="logo-hush-y2k">Wetan-Lib</div>
        <div class="school-id">SDN Berat Wetan 1</div>
    </div>
    <div class="nav-center-links">
        <a href="#">Beranda</a>
        <a href="#rekomendasi">Rekomendasi</a>
        <a href="#katalog">Semua Buku</a>
    </div>
    <div class="nav-right-icons"><i class="fas fa-search"></i></div>
</nav>

<section class="hero-section">
    <div class="hero-text-bg">READ MORE</div>
    <div class="hero-content">
        <div class="hero-book-wrapper">
            <div class="y2k-orb" style="top: -20px; left: -20px;"></div>
            <div class="y2k-orb" style="bottom: 10px; right: -30px; background: var(--y2k-blue); box-shadow: 0 0 30px var(--y2k-blue);"></div>
            @if($popularBooks->first())
                <img src="{{ asset('storage/' . $popularBooks->first()->gambar) }}" alt="Hero Book">
            @else
                <img src="https://via.placeholder.com/380x580" alt="Default">
            @endif
        </div>
        <div class="hero-desc">
            <p style="text-transform: uppercase; letter-spacing: 3px; font-weight: 700;"># Trending Minggu Ini</p>
            <h2>{{ $popularBooks->first()->judul ?? 'Katalog Buku Digital' }}</h2>
            <p>{{ $popularBooks->first()->penulis ?? 'Tim SDN 1' }} • {{ $popularBooks->first()->kategori->nama ?? 'Umum' }}</p>
            <a href="#katalog" class="btn-y2k">Jelajahi Sekarang <i class="fas fa-chevron-right ms-2"></i></a>
        </div>
    </div>
</section>

<section id="rekomendasi">
    <div class="container-custom" style="text-align: center;">
        <p style="text-transform: uppercase; letter-spacing: 3px; font-weight: 700; color: #fff;">Pilihan Pustakawan</p>
        <h2 style="font-family: 'Unbounded'; font-size: 2.5rem; color: #fff; margin-bottom: 50px;">NEW ARRIVALS</h2>

        <div class="slider-container">
            <button class="nav-btn" onclick="slideScroll(-320)"><i class="fas fa-chevron-left"></i></button>
            
            <div class="slider-track" id="slider">
                @foreach($popularBooks as $index => $pb)
                <div class="slider-item">
                    <img src="{{ asset('storage/' . $pb->gambar) }}" alt="">
                    <h3 class="node-title">{{ Str::limit($pb->judul, 30) }}</h3>
                    <p class="node-author">{{ $pb->penulis ?? 'Tim SDN 1' }}</p>
                    <span class="tag-category">{{ $pb->kategori->nama ?? 'Umum' }}</span>
                    <div class="card-tren-rank">{{ $index + 1 }}</div>
                </div>
                @endforeach
            </div>

            <button class="nav-btn" onclick="slideScroll(320)"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>

<section id="katalog">
    <div class="container-custom">
        <div class="section-title">
            <i class="fas fa-swatchbook fa-2x mb-2" style="color: var(--y2k-blue);"></i>
            <h2><span>Semua</span> Arsip Buku</h2>
        </div>

        <div class="search-hush-cyber">
            <input type="text" id="pencarianCanggih" class="input-cyber" placeholder="Scan database for titles, authors, or ISBN...">
            <select id="kategoriCanggih" class="input-cyber select-cyber">
                <option value="">Semua Galaxy (Kategori)</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
            <button onclick="scanDatabase()" class="btn-y2k" style="margin: 0;"><i class="fas fa-barcode ms-2"></i> CARI</button>
        </div>

        <div class="katalog-grid" id="gridKatalog">
            @foreach($books as $b)
            <div class="book-node">
                <img src="{{ asset('storage/' . $b->gambar) }}" class="node-img" alt="">
                <h3 class="node-title">{{ Str::limit($b->judul, 35) }}</h3>
                <p class="node-author">{{ $b->penulis ?? 'Tim SDN 1' }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="tag-category">{{ $b->kategori->nama ?? 'Umum' }}</span>
                    <span style="font-size: 11px; color: var(--y2k-pink); font-weight: bold;">Stok: {{ $b->stok }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fungsi Slider Rekomendasi
    function slideScroll(amount) {
        document.getElementById('slider').scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }

    // Fungsi Pencarian AJAX
    function scanDatabase() {
        let cari = document.getElementById('pencarianCanggih').value;
        let kat = document.getElementById('kategoriCanggih').value;
        let grid = document.getElementById('gridKatalog');

        grid.style.opacity = '0.3'; // Efek loading

        fetch(`{{ route('katalog.filter') }}?search=${cari}&kategori=${kat}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                if(data.books.length > 0) {
                    data.books.forEach(b => {
                        let img = b.gambar ? `/storage/${b.gambar}` : 'https://via.placeholder.com/230x280?text=No+Image';
                        let katName = b.kategori ? b.kategori.nama : 'Umum';
                        html += `
                        <div class="book-node">
                            <img src="${img}" class="node-img" alt="">
                            <h3 class="node-title">${b.judul}</h3>
                            <p class="node-author">${b.penulis || 'Tim SDN 1'}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="tag-category">${katName}</span>
                                <span style="font-size: 11px; color: var(--y2k-pink); font-weight: bold;">Stok: ${b.stok}</span>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="text-center w-100 py-5"><h4>DATA NOT FOUND IN ARCHIVE</h4><p>Yah, bukunya tidak ketemu...</p></div>';
                }
                grid.innerHTML = html;
                grid.style.opacity = '1';
            });
    }
</script>
@endsection