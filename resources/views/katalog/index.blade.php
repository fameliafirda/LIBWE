@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE & RESET (DARK & COLORFUL Y2K)
       ============================================================ */
    :root {
        --color-black: #0a0a0a; 
        --color-1: #cdb4db; 
        --color-2: #ffc8dd; 
        --color-3: #ffafcc; 
        --color-4: #bde0fe; 
        --color-5: #a2d2ff; 
        --glass: rgba(15, 15, 15, 0.6);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    /* Reset AdminLTE */
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

    /* Grid Background */
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background-image: 
            linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: 0;
        pointer-events: none;
    }

    .bg-glow {
        position: fixed;
        width: 40vw; height: 40vw;
        border-radius: 50%;
        filter: blur(150px);
        z-index: 0;
        opacity: 0.15;
        pointer-events: none;
    }

    /* ============================================================
       2. NAVIGASI (DIBEDAKAN DARI LANDING - FULL WIDTH GLASS)
       =========================================================== */
    .libwe-nav {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 20px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(10, 10, 10, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 1000;
        border-bottom: 1px solid var(--glass-border);
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
        background: linear-gradient(to right, var(--color-4), var(--color-3));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 1px;
    }

    .nav-links { display: flex; gap: 30px; align-items: center; }
    .nav-links a { 
        text-decoration: none; 
        color: rgba(255,255,255,0.7); 
        font-weight: 600; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-links a:hover { color: var(--color-4); }

    /* ============================================================
       3. HERO & PENCARIAN (LEBIH COMPACT)
       =========================================================== */
    .hero-catalog {
        padding: 150px 20px 60px;
        text-align: center;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .hero-catalog h1 { 
        font-family: 'Unbounded', sans-serif; 
        font-size: clamp(2rem, 4vw, 3rem); 
        color: #fff; 
        margin-bottom: 10px; 
    }

    .hero-catalog p {
        color: var(--color-4);
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 40px;
        font-size: 0.9rem;
    }

    /* Kotak Pencarian Ala Marketplace */
    .search-aesthetic {
        display: flex;
        justify-content: center;
        gap: 10px;
        width: 100%;
        max-width: 900px;
        background: var(--glass);
        padding: 15px;
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 30px rgba(0,0,0,0.5);
    }

    .search-aesthetic input, .search-aesthetic select {
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 15px 25px;
        border-radius: 12px;
        color: #fff;
        outline: none;
        flex: 1;
        font-weight: 500;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .search-aesthetic input:focus, .search-aesthetic select:focus {
        border-color: var(--color-4);
    }

    .btn-cari {
        background: var(--color-4);
        color: var(--color-black);
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        font-family: 'Unbounded', sans-serif;
    }
    .btn-cari:hover { background: var(--color-5); transform: translateY(-2px); }

    /* ============================================================
       4. SLIDER REKOMENDASI (UKURAN COMPACT)
       =========================================================== */
    #top10 { padding: 40px 0 80px; position: relative; z-index: 10; }

    .section-title-wrapper {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title-wrapper h2 {
        font-family: 'Unbounded', sans-serif;
        font-size: 2rem;
        color: #fff;
    }

    .slider-wrapper {
        position: relative;
        width: 100%;
        padding: 0 60px;
    }

    .track-container {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 20px 10px;
        scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 200px; /* Diperkecil */
        max-width: 200px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        padding: 15px;
        border-radius: 20px;
        transition: 0.4s;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .slider-card:hover {
        transform: translateY(-10px);
        border-color: var(--color-3);
        box-shadow: 0 15px 30px rgba(255, 175, 204, 0.15);
    }

    .slider-card img {
        width: 100%; 
        height: 240px; /* Ukuran proporsional buku */
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 15px;
        background: #111;
    }

    .rank-badge {
        position: absolute;
        top: -10px; left: 15px;
        background: var(--color-3);
        color: var(--color-black);
        padding: 5px 15px;
        border-radius: 10px;
        font-family: 'Unbounded';
        font-size: 0.7rem;
        font-weight: 900;
        z-index: 2;
    }

    .book-title-small { font-family: 'Unbounded'; font-size: 0.9rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
    .book-author-small { color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 600; margin-bottom: 10px; }
    
    .borrow-stats {
        margin-top: auto;
        background: rgba(189, 224, 254, 0.1);
        color: var(--color-4);
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-align: center;
        border: 1px solid rgba(189, 224, 254, 0.2);
    }

    /* Tombol Navigasi Slider */
    .btn-nav-slider {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px; height: 45px;
        border-radius: 50%;
        background: var(--glass);
        color: #fff;
        border: 1px solid var(--glass-border);
        cursor: pointer;
        z-index: 100;
        transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
    }

    .btn-nav-slider:hover { background: var(--color-4); color: var(--color-black); }
    .btn-prev { left: 15px; }
    .btn-next { right: 15px; }

    /* ============================================================
       5. KOLEKSI BUKU (GRID ALA GRAMEDIA)
       =========================================================== */
    #koleksi { padding: 40px 60px 100px; position: relative; z-index: 10; }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); /* Diperkecil agar muat banyak */
        gap: 25px;
    }

    .book-item {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        padding: 12px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .book-item:hover {
        border-color: var(--color-4);
        box-shadow: 0 10px 25px rgba(189, 224, 254, 0.1);
        transform: translateY(-5px);
    }

    .img-box {
        width: 100%; 
        height: 240px; /* Ukuran seragam */
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        margin-bottom: 15px;
    }

    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.08); }

    .category-pill {
        position: absolute;
        top: 10px; right: 10px;
        padding: 4px 10px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(5px);
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--color-1);
        border: 1px solid var(--color-1);
    }

    .book-info-container {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .b-title { font-family: 'Unbounded'; font-size: 0.95rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
    .b-author { color: rgba(255,255,255,0.5); font-size: 0.75rem; margin-bottom: 10px; font-weight: 500; }
    
    .b-meta {
        margin-top: auto; /* Dorong ke bawah */
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        padding-top: 10px;
        border-top: 1px dashed rgba(255,255,255,0.1);
    }

    .b-year { color: rgba(255,255,255,0.7); }
    .b-stock { 
        font-weight: 800; 
        color: var(--color-5); 
        background: rgba(162, 210, 255, 0.1);
        padding: 3px 8px;
        border-radius: 5px;
    }

    /* Floating Back Button */
    .btn-kembali {
        position: fixed;
        bottom: 30px; right: 30px; /* Pindah ke kanan agar tidak ganggu tangan kiri di HP */
        background: var(--color-1);
        color: var(--color-black);
        padding: 15px 25px;
        border-radius: 50px;
        text-decoration: none !important;
        font-weight: 800;
        z-index: 1000;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 25px rgba(205, 180, 219, 0.3);
        transition: 0.3s;
        font-size: 0.85rem;
    }

    .btn-kembali:hover { transform: scale(1.05); background: #fff; }

    /* ============================================================
       RESPONSIVE DESIGN
       =========================================================== */
    @media (max-width: 992px) {
        .libwe-nav { padding: 15px 30px; }
        .hero-catalog { padding-top: 120px; }
        .search-aesthetic { flex-direction: column; }
        .btn-cari { padding: 15px; }
    }

    @media (max-width: 768px) {
        #koleksi, .slider-wrapper { padding-left: 20px; padding-right: 20px; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .img-box { height: 200px; }
        .hero-catalog h1 { font-size: 1.8rem; }
        .btn-nav-slider { display: none; } /* Hide slider arrows on mobile, let them swipe */
    }
</style>

<div class="bg-glow" style="top: 10%; right: -5%; background: var(--color-1);"></div>
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
    <h1>KATALOG <span style="color: var(--color-3);">BUKU</span></h1>
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
        <h2>BUKU TERPOPULER</h2>
    </div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider btn-prev" onclick="moveSlide(-220)"><i class="fas fa-chevron-left"></i></button>
        <button class="btn-nav-slider btn-next" onclick="moveSlide(220)"><i class="fas fa-chevron-right"></i></button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="{{ $pb->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                @else
                    <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                @endif
                
                <h3 class="book-title-small">{{ Str::limit($pb->judul, 30) }}</h3>
                <p class="book-author-small"><i class="fas fa-pen-nib"></i> {{ $pb->penulis ?? 'Anonim' }}</p>
                
                <div class="borrow-stats">
                    <i class="fas fa-fire"></i> Dipinjam: {{ $pb->jumlah_dipinjam ?? rand(10,100) }}x
                </div>
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
                <h3 class="b-title">{{ Str::limit($b->judul, 35) }}</h3>
                <p class="b-author">{{ $b->penulis ?? 'Tim SDN 1' }}</p>
                
                <div class="b-meta">
                    <span class="b-year"><i class="fas fa-calendar-alt"></i> {{ $b->tahun_terbit ?? '-' }}</span>
                    <span class="b-stock">Stok: {{ $b->stok ?? 0 }}</span>
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
    // Slider Logic
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // AJAX Filter Logic (Di-update dengan struktur HTML baru)
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
                    // Cek gambar
                    let img = b.gambar ? `/storage/${b.gambar}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                    let kname = b.kategori ? b.kategori.nama : 'Umum';
                    let tahun = b.tahun_terbit ? b.tahun_terbit : '-';
                    let stok = b.stok !== null ? b.stok : '0';
                    let penulis = b.penulis ? b.penulis : 'Anonim';
                    let judul = b.judul.length > 35 ? b.judul.substring(0,35) + '...' : b.judul;

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
                                <span class="b-year"><i class="fas fa-calendar-alt"></i> ${tahun}</span>
                                <span class="b-stock">Stok: ${stok}</span>
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

    // Trigger pencarian saat tekan enter
    document.getElementById('keyword').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterBuku();
        }
    });
</script>
@endsection