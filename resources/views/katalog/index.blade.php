@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE & RESET (Y2K FUTURISTIC)
       ============================================================ */
    :root {
        --color-black: #050505; 
        --color-1: #cdb4db; 
        --color-2: #ffc8dd; 
        --color-3: #ff007f; /* Neon Pink */
        --color-4: #00f3ff; /* Neon Cyan */
        --color-5: #a2d2ff; 
        --glass: rgba(15, 15, 15, 0.7);
        --glass-border: rgba(255, 255, 255, 0.15);
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
        color: #ffffff;
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background-image: 
            linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 40px 40px;
        z-index: 0;
        pointer-events: none;
    }

    .bg-glow {
        position: fixed;
        width: 45vw; height: 45vw;
        border-radius: 50%;
        filter: blur(150px);
        z-index: 0;
        opacity: 0.12;
        pointer-events: none;
        animation: pulseGlow 10s infinite alternate;
    }

    @keyframes pulseGlow {
        0% { transform: scale(1); opacity: 0.1; }
        100% { transform: scale(1.1); opacity: 0.2; }
    }

    /* ============================================================
       2. NAVIGASI (FLOATING BAR COMPACT)
       =========================================================== */
    .libwe-nav {
        position: fixed;
        top: 20px; left: 50%; transform: translateX(-50%);
        width: 95%; max-width: 1200px;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(10, 10, 10, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 1000;
        border: 1px solid var(--color-4);
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0, 243, 255, 0.1);
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--color-4);
        letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
    }

    .nav-links { display: flex; gap: 30px; align-items: center; }
    .nav-links a { 
        text-decoration: none; 
        color: #fff; 
        font-weight: 700; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-links a:hover { color: var(--color-3); text-shadow: 0 0 10px var(--color-3); }

    /* ============================================================
       3. HERO & SEARCH SECTION (FIT & COMPACT)
       =========================================================== */
    .hero-catalog {
        padding: 130px 20px 50px;
        text-align: center;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 40vh;
    }

    .hero-catalog h1 { 
        font-family: 'Unbounded', sans-serif; 
        font-size: clamp(2rem, 5vw, 3.5rem); 
        color: #fff; 
        margin-bottom: 5px; 
        text-shadow: 0 5px 15px rgba(0,0,0,0.8);
    }

    .hero-catalog p {
        color: var(--color-4);
        font-weight: 700;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin-bottom: 40px;
        font-size: 0.9rem;
        text-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
    }

    /* Kotak Pencarian */
    .search-aesthetic {
        display: flex;
        justify-content: center;
        gap: 15px;
        width: 100%;
        max-width: 850px;
        background: rgba(20, 20, 20, 0.8);
        padding: 15px;
        border-radius: 20px;
        border: 1px solid var(--color-3);
        box-shadow: 0 10px 30px rgba(255, 0, 127, 0.2);
        backdrop-filter: blur(10px);
    }

    .search-aesthetic input, .search-aesthetic select {
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid var(--glass-border);
        padding: 15px 25px;
        border-radius: 12px;
        color: #fff;
        outline: none;
        flex: 1;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: 0.3s;
    }

    .search-aesthetic input:focus, .search-aesthetic select:focus {
        border-color: var(--color-4);
        box-shadow: 0 0 10px rgba(0, 243, 255, 0.2);
    }

    .btn-cari {
        background: var(--color-3);
        color: #fff;
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        font-family: 'Unbounded', sans-serif;
        box-shadow: 0 0 15px rgba(255, 0, 127, 0.4);
    }
    .btn-cari:hover { background: #fff; color: var(--color-3); transform: scale(1.05); }

    /* ============================================================
       4. SECTION REKOMENDASI (SANGAT DIBEDAKAN DARI BACKGROUND)
       =========================================================== */
    #top10 { 
        padding: 60px 0; 
        position: relative; 
        z-index: 10; 
        background: linear-gradient(to right, rgba(255, 0, 127, 0.05), rgba(0, 243, 255, 0.05));
        border-top: 2px dashed rgba(255, 0, 127, 0.3);
        border-bottom: 2px dashed rgba(0, 243, 255, 0.3);
        margin-bottom: 40px;
    }

    .section-title-wrapper { text-align: center; margin-bottom: 30px; }
    .section-title-wrapper h2 {
        font-family: 'Unbounded', sans-serif;
        font-size: 2rem;
        color: #fff;
        display: inline-block;
        padding: 10px 30px;
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid var(--color-4);
        border-radius: 50px;
        box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);
    }

    .slider-wrapper { position: relative; width: 100%; padding: 0 50px; }

    .track-container {
        display: flex; gap: 20px; overflow-x: auto;
        scroll-behavior: smooth; padding: 20px 10px;
        scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    /* Card Rekomendasi */
    .slider-card {
        min-width: 180px; max-width: 180px; /* Gramedia Size */
        background: rgba(10, 10, 10, 0.9);
        border: 1px solid var(--color-3);
        padding: 15px; border-radius: 15px;
        transition: 0.4s; position: relative;
        display: flex; flex-direction: column;
        box-shadow: 0 10px 20px rgba(255, 0, 127, 0.15);
    }

    .slider-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(255, 0, 127, 0.4); }

    .slider-card img {
        width: 100%; height: 230px; object-fit: cover;
        border-radius: 10px; margin-bottom: 12px; background: #111;
    }

    .rank-badge {
        position: absolute; top: -10px; left: 10px;
        background: var(--color-4); color: var(--color-black);
        padding: 5px 12px; border-radius: 8px;
        font-family: 'Unbounded'; font-size: 0.75rem; font-weight: 900;
        z-index: 2; box-shadow: 0 5px 15px rgba(0, 243, 255, 0.4);
    }

    .borrow-stats {
        position: absolute; top: 10px; right: -10px;
        background: var(--color-3); color: #fff;
        padding: 5px 10px; border-radius: 20px 0 0 20px;
        font-size: 0.7rem; font-weight: 800;
        box-shadow: 0 5px 10px rgba(255, 0, 127, 0.5);
    }

    .book-title-small { font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
    .book-author-small { color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 600; margin-bottom: 0; }

    .btn-nav-slider {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 45px; height: 45px; border-radius: 50%;
        background: var(--color-black); color: var(--color-3);
        border: 2px solid var(--color-3); cursor: pointer;
        z-index: 100; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-nav-slider:hover { background: var(--color-3); color: #fff; box-shadow: 0 0 15px var(--color-3); }
    .btn-prev { left: 15px; }
    .btn-next { right: 15px; }

    /* ============================================================
       5. KOLEKSI BUKU (GRID ALA GRAMEDIA)
       =========================================================== */
    #koleksi { padding: 20px 50px 100px; position: relative; z-index: 10; }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); /* Ukuran pas Gramedia */
        gap: 25px;
    }

    .book-item {
        background: rgba(15, 15, 15, 0.8);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 12px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .book-item:hover {
        border-color: var(--color-4);
        box-shadow: 0 10px 25px rgba(0, 243, 255, 0.2);
        transform: translateY(-5px);
    }

    .img-box {
        width: 100%; height: 220px; /* Seragam dan compact */
        background: #000; border-radius: 8px; overflow: hidden;
        position: relative; margin-bottom: 15px;
    }

    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.08); }

    .category-pill {
        position: absolute; top: 8px; right: 8px;
        padding: 4px 8px; background: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px); border-radius: 5px;
        font-size: 0.65rem; font-weight: 700;
        color: var(--color-4); border: 1px solid var(--color-4);
    }

    .book-info-container { display: flex; flex-direction: column; flex-grow: 1; }
    .b-title { 
        font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; 
        color: #fff; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .b-author { color: rgba(255,255,255,0.5); font-size: 0.75rem; margin-bottom: 10px; font-weight: 600; }
    
    .b-meta {
        margin-top: auto; display: flex; flex-direction: column; gap: 5px;
        font-size: 0.75rem; padding-top: 10px;
        border-top: 1px dashed rgba(255,255,255,0.1);
    }

    .b-meta-row { display: flex; justify-content: space-between; align-items: center; }
    .b-year { color: rgba(255,255,255,0.7); }
    .b-stock { font-weight: 800; color: var(--color-3); }

    /* Floating Back Button */
    .btn-kembali {
        position: fixed; bottom: 30px; right: 30px; 
        background: var(--color-black); color: var(--color-4);
        border: 2px solid var(--color-4); padding: 12px 25px;
        border-radius: 50px; text-decoration: none !important;
        font-weight: 800; z-index: 1000;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 25px rgba(0, 243, 255, 0.2);
        transition: 0.3s; font-size: 0.85rem;
    }

    .btn-kembali:hover { transform: scale(1.05); background: var(--color-4); color: var(--color-black); }

    /* ============================================================
       RESPONSIVE DESIGN
       =========================================================== */
    @media (max-width: 992px) {
        .libwe-nav { padding: 15px 25px; width: 90%; }
        .search-aesthetic { flex-direction: column; }
        .btn-cari { padding: 15px; }
    }

    @media (max-width: 768px) {
        #koleksi, .slider-wrapper { padding-left: 20px; padding-right: 20px; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .img-box { height: 190px; }
        .slider-card img { height: 200px; }
        .hero-catalog h1 { font-size: 1.8rem; }
        .btn-nav-slider { display: none; } 
    }
</style>

<div class="bg-glow" style="top: 10%; right: -5%; background: var(--color-3);"></div>
<div class="bg-glow" style="bottom: 20%; left: -10%; background: var(--color-4);"></div>

<nav class="libwe-nav">
    <div class="brand-libwe">LIBWE</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="#top10">Populer</a>
        <a href="#koleksi">Katalog</a>
    </div>
</nav>

<section class="hero-catalog">
    <h1>KATALOG <span style="color: var(--color-4);">BUKU</span></h1>
    <p>Eksplorasi Dunia Tanpa Batas</p>

    <div class="search-aesthetic">
        <input type="text" id="keyword" placeholder="Cari judul, penulis, atau penerbit...">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">
            <i class="fas fa-search"></i> CARI
        </button>
    </div>
</section>

<section id="top10">
    <div class="section-title-wrapper">
        <h2>🔥 BUKU TERPOPULER</h2>
    </div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider btn-prev" onclick="moveSlide(-200)"><i class="fas fa-chevron-left"></i></button>
        <button class="btn-nav-slider btn-next" onclick="moveSlide(200)"><i class="fas fa-chevron-right"></i></button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                
                <div class="borrow-stats">
                    <i class="fas fa-fire"></i> Dipinjam: {{ $pb->jumlah_dipinjam ?? 0 }}x
                </div>

                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="{{ $pb->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                @endif
                
                <h3 class="book-title-small">{{ Str::limit($pb->judul, 30) }}</h3>
                <p class="book-author-small"><i class="fas fa-pen-nib"></i> {{ $pb->penulis ?? 'Anonim' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">
        @foreach($books as $b)
        <div class="book-item">
            <div class="img-box">
                <span class="category-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                @if($b->gambar)
                    <img src="{{ asset('storage/'.$b->gambar) }}" alt="{{ $b->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                @endif
            </div>
            
            <div class="book-info-container">
                <h3 class="b-title">{{ $b->judul }}</h3>
                <p class="b-author">{{ $b->penulis ?? 'Tim SDN 1' }}</p>
                
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

    <div class="d-flex justify-content-center" style="margin-top: 50px;">
        {{ $books->links() }}
    </div>
</section>

<a href="{{ url('/') }}" class="btn-kembali">
    <i class="fas fa-home"></i> HOME
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
                data.books.forEach(b => {
                    let img = b.gambar ? `/storage/${b.gambar}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                    let kname = b.kategori ? b.kategori.nama : 'Umum';
                    let tahun = b.tahun_terbit ? b.tahun_terbit : '-';
                    let stok = b.stok !== null ? b.stok : '0';
                    let penulis = b.penulis ? b.penulis : 'Anonim';

                    html += `
                    <div class="book-item">
                        <div class="img-box">
                            <span class="category-pill">${kname}</span>
                            <img src="${img}" onerror="this.src='{{ asset("web-perpus/img/bukubaru.png") }}'">
                        </div>
                        <div class="book-info-container">
                            <h3 class="b-title">${b.judul}</h3>
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
                
                if(data.books.length === 0) {
                    html = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #fff;"><h3>Buku tidak ditemukan.</h3></div>';
                }

                container.innerHTML = html;
                container.style.opacity = '1';
            })
            .catch(err => {
                console.error(err);
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