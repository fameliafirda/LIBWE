@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Unbounded:wght@700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       1. COLOR PALETTE & RESET
       ============================================================ */
    :root {
        --color-1: #cdb4db; /* Soft Purple */
        --color-2: #ffc8dd; /* Soft Pink */
        --color-3: #ffafcc; /* Hot Pink Pastel */
        --color-4: #bde0fe; /* Sky Blue */
        --color-5: #a2d2ff; /* Bright Blue */
        --color-black: #0a0a0a;
        --glass: rgba(255, 255, 255, 0.03);
    }

    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: var(--color-black) !important; 
        min-height: 100vh;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--color-black);
        color: #fff;
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Floating Background Elements */
    .bg-glow {
        position: fixed;
        width: 40vw; height: 40vw;
        border-radius: 50%;
        filter: blur(120px);
        z-index: 0;
        opacity: 0.15;
        pointer-events: none;
    }

    /* ============================================================
       2. NAVIGATION (HUSH STYLE)
       =========================================================== */
    .libwe-nav {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 25px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(10, 10, 10, 0.6);
        backdrop-filter: blur(20px);
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        background: linear-gradient(to right, var(--color-4), var(--color-1));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -2px;
    }

    .nav-links { display: flex; gap: 40px; }
    .nav-links a { 
        text-decoration: none; 
        color: rgba(255,255,255,0.7); 
        font-weight: 600; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-links a:hover { color: var(--color-2); text-shadow: 0 0 10px var(--color-2); }

    /* ============================================================
       3. HERO SECTION (BUKU UTAMA)
       =========================================================== */
    .hero {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .hero-bg-text {
        position: absolute;
        font-family: 'Syne', sans-serif;
        font-size: 15vw;
        font-weight: 800;
        color: transparent;
        -webkit-text-stroke: 1px rgba(255,255,255,0.05);
        z-index: 1;
        white-space: nowrap;
        user-select: none;
    }

    .hero-img-container {
        position: relative;
        z-index: 2;
        width: 420px;
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .hero-img-container:hover { transform: scale(1.05) rotate(-2deg); }

    .hero-img-container img {
        width: 100%;
        filter: drop-shadow(0 0 50px rgba(189, 224, 254, 0.3));
        animation: floatImg 6s ease-in-out infinite;
    }

    @keyframes floatImg {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    .hero-content {
        position: absolute;
        bottom: 10%;
        text-align: center;
        z-index: 3;
    }

    .hero-content h1 { font-family: 'Unbounded'; font-size: 3rem; margin-bottom: 10px; }

    /* ============================================================
       4. TOP 10 BUKU TERLARIS (SLIDER)
       =========================================================== */
    #top10 {
        padding: 120px 0;
        background: linear-gradient(to bottom, transparent, rgba(205, 180, 219, 0.05));
    }

    .section-label {
        font-family: 'Unbounded';
        font-size: 0.7rem;
        letter-spacing: 5px;
        color: var(--color-3);
        margin-bottom: 15px;
        display: block;
    }

    .slider-wrapper {
        display: flex;
        align-items: center;
        gap: 30px;
        margin-top: 50px;
        padding: 0 60px;
    }

    .track-container {
        display: flex;
        gap: 30px;
        overflow: hidden;
        scroll-behavior: smooth;
        padding: 20px 10px;
    }

    .slider-card {
        min-width: 280px;
        background: var(--glass);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 30px;
        border-radius: 40px;
        text-align: center;
        position: relative;
        transition: 0.4s;
    }

    .slider-card:hover {
        background: rgba(255,255,255,0.08);
        border-color: var(--color-4);
        transform: translateY(-15px);
    }

    .slider-card img {
        width: 180px;
        height: 260px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        margin-bottom: 25px;
    }

    .rank-badge {
        position: absolute;
        top: -10px; left: -10px;
        width: 50px; height: 50px;
        background: var(--color-3);
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Unbounded'; font-weight: 900;
        box-shadow: 0 10px 20px rgba(255, 175, 204, 0.4);
    }

    .btn-nav-slider {
        width: 60px; height: 60px;
        border-radius: 50%;
        border: 1px solid var(--color-4);
        background: transparent;
        color: var(--color-4);
        font-size: 1.5rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-nav-slider:hover { background: var(--color-4); color: var(--color-black); }

    /* ============================================================
       5. KOLEKSI BUKU (GRID)
       =========================================================== */
    #koleksi { padding: 120px 60px; }

    .search-aesthetic {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 80px;
    }

    .search-aesthetic input, .search-aesthetic select {
        background: var(--glass);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 18px 30px;
        border-radius: 20px;
        color: #fff;
        outline: none;
        font-family: 'Plus Jakarta Sans';
    }

    .btn-cari {
        background: var(--color-5);
        color: var(--color-black);
        border: none;
        padding: 0 40px;
        border-radius: 20px;
        font-weight: 800;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-cari:hover { transform: scale(1.05); background: var(--color-4); }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 50px;
    }

    .book-item {
        transition: 0.4s;
        text-align: left;
    }

    .book-item:hover { transform: translateY(-10px); }

    .img-box {
        width: 100%;
        height: 380px;
        background: #111;
        border-radius: 40px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 25px;
        border: 1px solid rgba(255,255,255,0.05);
        overflow: hidden;
        position: relative;
    }

    .img-box img { width: 85%; height: 85%; object-fit: cover; border-radius: 15px; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.1); }

    .category-pill {
        position: absolute;
        top: 20px; right: 20px;
        padding: 6px 15px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--color-1);
        border: 1px solid var(--color-1);
    }

    .book-title { font-family: 'Unbounded'; font-size: 1.1rem; margin-bottom: 5px; color: #fff; }
    .book-author { color: rgba(255,255,255,0.4); font-size: 0.85rem; }

    /* ============================================================
       6. BACK BUTTON (FLOATING)
       =========================================================== */
    .btn-kembali {
        position: fixed;
        bottom: 40px; left: 40px;
        background: var(--color-1);
        color: var(--color-black);
        padding: 15px 35px;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.85rem;
        z-index: 1000;
        box-shadow: 0 15px 30px rgba(205, 180, 219, 0.3);
        transition: 0.4s;
        display: flex; align-items: center; gap: 10px;
    }

    .btn-kembali:hover {
        transform: translateX(10px) scale(1.05);
        background: var(--color-2);
    }

</style>

<div class="bg-glow" style="top: -10%; right: -5%; background: var(--color-1);"></div>
<div class="bg-glow" style="bottom: 10%; left: -5%; background: var(--color-4);"></div>

<nav class="libwe-nav">
    <div class="brand-libwe">LIBWE</div>
    <div class="nav-links">
        <a href="#">Home</a>
        <a href="#top10">Top Books</a>
        <a href="#koleksi">Koleksi</a>
    </div>
    <div style="width: 100px; text-align: right;"><i class="fas fa-search"></i></div>
</nav>

<a href="{{ url('/') }}" class="btn-kembali">
    <i class="fas fa-chevron-left"></i> KEMBALI
</a>

<section class="hero">
    <div class="hero-bg-text">LIBRARY.SDN1</div>
    <div class="hero-content">
        <div class="hero-img-container">
            <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Main Book">
        </div>
        <h1 style="color: var(--color-2)">READ MORE.</h1>
        <p style="letter-spacing: 5px; color: rgba(255,255,255,0.5)">DISCOVER YOUR FUTURE</p>
    </div>
</section>

<section id="top10">
    <div style="text-align: center;">
        <span class="section-label">THE BEST CHOICE</span>
        <h2 style="font-family: 'Unbounded'; font-size: 2.5rem;">TOP 10 BUKU TERLARIS</h2>
    </div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider" onclick="moveSlide(-320)"><i class="fas fa-arrow-left"></i></button>
        
        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">{{ $index + 1 }}</div>
                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="">
                @endif
                <h3 class="book-title">{{ Str::limit($pb->judul, 25) }}</h3>
                <p class="book-author">{{ $pb->penulis ?? 'Anonymous' }}</p>
            </div>
            @endforeach
        </div>

        <button class="btn-nav-slider" onclick="moveSlide(320)"><i class="fas fa-arrow-right"></i></button>
    </div>
</section>

<section id="koleksi">
    <div style="text-align: center; margin-bottom: 60px;">
        <span class="section-label">EXPLORE ARCHIVE</span>
        <h2 style="font-family: 'Unbounded'; font-size: 2.5rem;">KOLEKSI BUKU</h2>
    </div>

    <div class="search-aesthetic">
        <input type="text" id="keyword" placeholder="Cari judul atau penulis..." style="width: 400px;">
        <select id="kat_id" style="width: 250px;">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">TEMUKAN</button>
    </div>

    <div class="book-grid" id="containerKoleksi">
        @foreach($books as $b)
        <div class="book-item">
            <div class="img-box">
                <span class="category-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                @if($b->gambar)
                    <img src="{{ asset('storage/'.$b->gambar) }}" alt="">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="">
                @endif
            </div>
            <h3 class="book-title">{{ Str::limit($b->judul, 40) }}</h3>
            <p class="book-author">{{ $b->penulis ?? 'Tim SDN 1' }}</p>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $books->links() }}
    </div>
</section>

<script>
    // Slider Logic
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // Filter Logic AJAX
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');

        container.style.opacity = '0.3';

        fetch(`{{ route('katalog.filter') }}?search=${keyword}&kategori=${kategori}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.books.forEach(b => {
                    let img = b.gambar ? `/storage/${b.gambar}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                    let kname = b.kategori ? b.kategori.nama : 'Umum';
                    html += `
                    <div class="book-item">
                        <div class="img-box">
                            <span class="category-pill">${kname}</span>
                            <img src="${img}">
                        </div>
                        <h3 class="book-title">${b.judul}</h3>
                        <p class="book-author">${b.penulis || 'Tim SDN 1'}</p>
                    </div>`;
                });
                container.innerHTML = html;
                container.style.opacity = '1';
            });
    }
</script>
@endsection