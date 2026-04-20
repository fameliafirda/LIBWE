@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Unbounded:wght@700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       1. RESET & VARIABLE WARNA (PASTEL POP)
       ============================================================ */
    :root {
        --color-1: #cdb4db; /* Ungu Muda */
        --color-2: #ffc8dd; /* Pink Lembut */
        --color-3: #ffafcc; /* Hot Pink Pastel */
        --color-4: #bde0fe; /* Biru Langit */
        --color-5: #a2d2ff; /* Biru Cerah */
        --color-dark: #2b2d42;
        --color-bg: #fdfcfd;
    }

    /* Hilangkan Sidebar & Header AdminLTE Agar Full Custom */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: var(--color-bg) !important; 
        min-height: 100vh;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--color-bg);
        color: var(--color-dark);
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Dekorasi Glow Latar Belakang */
    .bg-glow {
        position: fixed;
        width: 60vw; height: 60vw;
        border-radius: 50%;
        filter: blur(120px);
        z-index: 0;
        opacity: 0.25;
        pointer-events: none;
    }

    /* ============================================================
       2. NAVIGASI (FIXED TOP)
       =========================================================== */
    .libwe-nav {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 20px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        z-index: 1000;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: 0.4s;
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        background: linear-gradient(to right, var(--color-3), var(--color-5));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .nav-links { display: flex; gap: 35px; }
    .nav-links a { 
        text-decoration: none; 
        color: var(--color-dark); 
        font-weight: 700; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        position: relative;
    }

    .nav-links a::after {
        content: '';
        position: absolute; width: 0; height: 3px;
        bottom: -5px; left: 0; background: var(--color-3);
        transition: 0.3s;
    }

    .nav-links a:hover::after { width: 100%; }

    /* ============================================================
       3. HERO SECTION & RUNNING TEXT (MARQUEE)
       =========================================================== */
    .hero {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .marquee-wrapper {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        width: 100%;
        z-index: 1;
        pointer-events: none;
        overflow: hidden;
    }

    .marquee-content {
        display: flex;
        gap: 50px;
        animation: scrollText 30s linear infinite;
    }

    .marquee-item {
        font-family: 'Syne', sans-serif;
        font-size: 12vw;
        font-weight: 800;
        white-space: nowrap;
        background: linear-gradient(to right, var(--color-1), var(--color-3), var(--color-5));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 0.12;
        text-transform: uppercase;
    }

    @keyframes scrollText {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .hero-img-container {
        position: relative;
        z-index: 5;
        width: 420px;
        transition: 0.8s cubic-bezier(0.2, 1, 0.3, 1);
    }

    .hero-img-container:hover { transform: scale(1.05) rotate(-3deg); }

    .hero-img-container img {
        width: 100%;
        filter: drop-shadow(0 30px 60px rgba(0,0,0,0.15));
        animation: floatMain 6s ease-in-out infinite;
    }

    @keyframes floatMain {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    .hero-content {
        position: absolute;
        bottom: 8%;
        text-align: center;
        z-index: 10;
    }

    .hero-content h1 { 
        font-family: 'Unbounded'; 
        font-size: 4rem; 
        color: var(--color-dark); 
        margin: 0;
        text-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    /* ============================================================
       4. SLIDER REKOMENDASI (TOMBOL SAMPING TENGAH)
       =========================================================== */
    #top10 { padding: 120px 0; position: relative; }

    .slider-box {
        position: relative;
        width: 100%;
        padding: 0 100px;
    }

    .track-container {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 40px 10px;
        scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 300px;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.05);
        padding: 35px;
        border-radius: 45px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.04);
        transition: 0.4s;
        position: relative;
    }

    .slider-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 25px 50px rgba(205, 180, 219, 0.3);
        border-color: var(--color-1);
    }

    .slider-card img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .rank-tag {
        position: absolute;
        top: -15px; left: 50%;
        transform: translateX(-50%);
        background: var(--color-3);
        color: #fff;
        padding: 8px 20px;
        border-radius: 20px;
        font-family: 'Unbounded';
        font-size: 0.8rem;
        font-weight: 800;
        box-shadow: 0 8px 15px rgba(255, 175, 204, 0.4);
    }

    /* Tombol Navigasi Slider */
    .btn-nav-slider {
        position: absolute;
        top: 55%;
        transform: translateY(-50%);
        width: 65px; height: 65px;
        border-radius: 50%;
        background: #fff;
        color: var(--color-dark);
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        cursor: pointer;
        z-index: 50;
        transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
    }

    .btn-nav-slider:hover {
        background: var(--color-dark);
        color: #fff;
        transform: translateY(-50%) scale(1.1);
    }
    .btn-left { left: 25px; }
    .btn-right { right: 25px; }

    /* ============================================================
       5. KOLEKSI BUKU & PENCARIAN
       =========================================================== */
    #koleksi { 
        padding: 100px 60px; 
        background: #fff; 
        border-radius: 80px 80px 0 0;
        box-shadow: 0 -20px 50px rgba(0,0,0,0.02);
    }

    .search-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 80px;
        background: #f8f9fa;
        padding: 25px;
        border-radius: 35px;
        max-width: 1000px;
        margin: 0 auto 80px auto;
    }

    .search-container input, .search-container select {
        background: #fff;
        border: 1px solid #eee;
        padding: 18px 25px;
        border-radius: 20px;
        outline: none;
        font-weight: 600;
        flex: 1;
        transition: 0.3s;
    }

    .search-container input:focus { border-color: var(--color-5); box-shadow: 0 0 15px rgba(162, 210, 255, 0.3); }

    .btn-temukan {
        background: var(--color-dark);
        color: #fff;
        border: none;
        padding: 0 45px;
        border-radius: 20px;
        font-weight: 800;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-temukan:hover { background: var(--color-3); transform: translateY(-3px); }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 50px;
    }

    .book-card {
        transition: 0.4s;
    }

    .book-card:hover { transform: translateY(-12px); }

    .image-holder {
        width: 100%; height: 380px;
        background: #f1f3f5;
        border-radius: 40px;
        overflow: hidden;
        position: relative;
        margin-bottom: 25px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }

    .image-holder img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .book-card:hover .image-holder img { transform: scale(1.1); }

    .badge-kategori {
        position: absolute;
        top: 20px; right: 20px;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px 18px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 800;
        color: var(--color-5);
        backdrop-filter: blur(5px);
    }

    .book-info h3 { font-family: 'Unbounded'; font-size: 1.1rem; color: var(--color-dark); margin-bottom: 8px; line-height: 1.4; }
    .book-info p { color: #888; font-size: 0.9rem; font-weight: 600; }

    /* Tombol Kembali Floating */
    .btn-back-floating {
        position: fixed;
        bottom: 40px; left: 40px;
        background: var(--color-dark);
        color: #fff;
        padding: 18px 30px;
        border-radius: 100px;
        text-decoration: none !important;
        font-weight: 800;
        z-index: 1000;
        display: flex; align-items: center; gap: 12px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        transition: 0.4s;
    }

    .btn-back-floating:hover { background: var(--color-3); transform: scale(1.05) translateX(5px); color: #fff; }

</style>

<div class="bg-glow" style="top: -10%; left: -10%; background: var(--color-4);"></div>
<div class="bg-glow" style="bottom: 10%; right: -10%; background: var(--color-2);"></div>

<nav class="libwe-nav" id="mainNav">
    <div class="brand-libwe">LIBWE</div>
    <div class="nav-links">
        <a href="#">Beranda</a>
        <a href="#top10">Populer</a>
        <a href="#koleksi">Koleksi Buku</a>
    </div>
    <div style="width: 100px; text-align: right; cursor: pointer;"><i class="fas fa-search fa-lg"></i></div>
</nav>

<a href="{{ url('/') }}" class="btn-back-floating">
    <i class="fas fa-chevron-left"></i> KEMBALI KE MENU
</a>

<section class="hero">
    <div class="marquee-wrapper">
        <div class="marquee-content">
            <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
            <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
            <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
            <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
        </div>
    </div>

    <div class="hero-img-container">
        <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Buku Unggulan">
    </div>

    <div class="hero-content">
        <h1>MARI <span style="color: var(--color-3)">BACA.</span></h1>
        <p style="letter-spacing: 6px; font-weight: 800; color: var(--color-5); margin-top: 10px;">JELAJAHI ILMU PENGETAHUAN</p>
    </div>
</section>

<section id="top10">
    <div style="text-align: center; margin-bottom: 60px;">
        <span style="color: var(--color-1); font-weight: 800; letter-spacing: 3px; font-size: 0.8rem; text-transform: uppercase;">Rekomendasi Terbaik</span>
        <h2 style="font-family: 'Unbounded'; font-size: 2.5rem; margin-top: 10px;">TOP 10 BUKU TERLARIS</h2>
    </div>

    <div class="slider-box">
        <button class="btn-nav-slider btn-left" onclick="moveSlide(-330)"><i class="fas fa-arrow-left"></i></button>
        <button class="btn-nav-slider btn-right" onclick="moveSlide(330)"><i class="fas fa-arrow-right"></i></button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-tag">PERINGKAT #{{ $index + 1 }}</div>
                <img src="{{ $pb->gambar ? asset('storage/'.$pb->gambar) : asset('web-perpus/img/bukubaru.png') }}" alt="{{ $pb->judul }}">
                <div class="book-info">
                    <h3>{{ Str::limit($pb->judul, 22) }}</h3>
                    <p>{{ $pb->penulis ?? 'Penulis Favorit' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="koleksi">
    <div style="text-align: center; margin-bottom: 60px;">
        <h2 style="font-family: 'Unbounded'; font-size: 2.5rem;">SEMUA KOLEKSI KAMI</h2>
        <p style="opacity: 0.5; font-weight: 600;">Temukan ribuan buku menarik untuk dipelajari</p>
    </div>

    <div class="search-container">
        <input type="text" id="keyword" placeholder="Cari judul buku, penulis, atau penerbit...">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-temukan" onclick="filterBuku()">TEMUKAN</button>
    </div>

    <div class="book-grid" id="containerKoleksi">
        @foreach($books as $b)
        <div class="book-card">
            <div class="image-holder">
                <span class="badge-kategori">{{ $b->kategori->nama ?? 'Umum' }}</span>
                <img src="{{ $b->gambar ? asset('storage/'.$b->gambar) : asset('web-perpus/img/bukubaru.png') }}" alt="{{ $b->judul }}">
            </div>
            <div class="book-info">
                <h3>{{ Str::limit($b->judul, 45) }}</h3>
                <p>{{ $b->penulis ?? 'Tim SDN 1' }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $books->links() }}
    </div>
</section>

<script>
    // 1. Logika Sticky Navbar saat scroll
    window.onscroll = function() {
        let nav = document.getElementById("mainNav");
        if (window.pageYOffset > 50) {
            nav.style.padding = "15px 60px";
            nav.style.background = "rgba(255, 255, 255, 0.95)";
        } else {
            nav.style.padding = "20px 60px";
            nav.style.background = "rgba(255, 255, 255, 0.8)";
        }
    };

    // 2. Logika Geser Slider Rekomendasi
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // 3. Logika Filter Pencarian (AJAX)
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');

        // Beri efek loading sederhana
        container.style.opacity = '0.3';
        container.style.pointerEvents = 'none';

        fetch(`{{ route('katalog.filter') }}?search=${keyword}&kategori=${kategori}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.books.length > 0) {
                    data.books.forEach(b => {
                        let img = b.gambar ? `/storage/${b.gambar}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                        let kname = b.kategori ? b.kategori.nama : 'Umum';
                        html += `
                        <div class="book-card">
                            <div class="image-holder">
                                <span class="badge-kategori">${kname}</span>
                                <img src="${img}">
                            </div>
                            <div class="book-info">
                                <h3>${b.judul}</h3>
                                <p>${b.penulis || 'Tim SDN 1'}</p>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="col-12 text-center"><h3>Maaf, buku tidak ditemukan...</h3></div>';
                }
                container.innerHTML = html;
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            })
            .catch(err => {
                console.error(err);
                container.style.opacity = '1';
            });
    }

    // Jalankan filter saat menekan tombol Enter di input keyword
    document.getElementById('keyword').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterBuku();
        }
    });
</script>
@endsection