@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE (SOFT Y2K) & RESET
       ============================================================ */
    :root {
        --bg-dark: #07070a; /* Lebih ke dark blue/purple untuk bedain dari landing */
        --lavender: #cdb4db;
        --soft-pink: #ffc8dd;
        --baby-blue: #a2d2ff;
        --glass: rgba(15, 15, 20, 0.65);
        --glass-border: rgba(205, 180, 219, 0.15);
        --text-subtle: rgba(255, 255, 255, 0.6);
    }

    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: var(--bg-dark) !important; 
        min-height: 100vh;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-dark);
        color: #ffffff;
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Background Pattern - Beda dari Landing */
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(205, 180, 219, 0.04), transparent 25%),
            radial-gradient(circle at 85% 30%, rgba(162, 210, 255, 0.04), transparent 25%);
        z-index: 0;
        pointer-events: none;
    }

    /* ============================================================
       2. NAVIGASI (FULL WIDTH)
       =========================================================== */
    .catalog-nav {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 20px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(7, 7, 10, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 1000;
        border-bottom: 1px solid var(--glass-border);
    }

    .brand-libwe {
        font-family: 'Unbounded', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--lavender);
        letter-spacing: 1px;
    }

    .nav-links { display: flex; gap: 30px; align-items: center; }
    .nav-links a { 
        text-decoration: none; 
        color: var(--text-subtle); 
        font-weight: 600; 
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--baby-blue); }

    /* ============================================================
       3. HERO PENCARIAN (COMPACT HEADER)
       =========================================================== */
    .hero-catalog {
        padding: 140px 20px 60px;
        text-align: center;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: linear-gradient(180deg, rgba(205, 180, 219, 0.05) 0%, transparent 100%);
        border-bottom: 1px solid var(--glass-border);
    }

    .hero-catalog h1 { 
        font-family: 'Unbounded', sans-serif; 
        font-size: clamp(2rem, 4vw, 3rem); 
        color: #fff; 
        margin-bottom: 10px; 
    }

    .hero-catalog h1 span {
        color: var(--baby-blue);
    }

    .hero-catalog p {
        color: var(--lavender);
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 40px;
        font-size: 0.85rem;
    }

    /* Search Box Ala Marketplace */
    .search-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        width: 100%;
        max-width: 900px;
        background: var(--glass);
        padding: 12px;
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .search-container input, .search-container select {
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid transparent;
        padding: 15px 20px;
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
        background: rgba(0, 0, 0, 0.6);
    }

    .btn-cari {
        background: var(--baby-blue);
        color: var(--bg-dark);
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        font-family: 'Unbounded', sans-serif;
        font-size: 0.9rem;
    }
    .btn-cari:hover { background: #fff; transform: scale(1.02); }

    /* ============================================================
       4. SLIDER REKOMENDASI (DATA REALTIME DB)
       =========================================================== */
    #top10 { 
        padding: 60px 0; 
        position: relative; 
        z-index: 10; 
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
        font-family: 'Unbounded', sans-serif;
        font-size: 1.8rem;
        color: #fff;
    }

    .slider-wrapper { position: relative; width: 100%; padding: 0 60px; }

    .track-container {
        display: flex; gap: 20px; overflow-x: auto;
        scroll-behavior: smooth; padding: 20px 10px;
        scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    /* Card Rekomendasi Ala Gramedia */
    .slider-card {
        min-width: 190px; max-width: 190px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        padding: 15px; border-radius: 15px;
        transition: 0.4s; position: relative;
        display: flex; flex-direction: column;
    }

    .slider-card:hover { 
        transform: translateY(-8px); 
        border-color: var(--soft-pink); 
        box-shadow: 0 10px 25px rgba(255, 200, 221, 0.15); 
    }

    .slider-card img {
        width: 100%; height: 250px; object-fit: cover;
        border-radius: 10px; margin-bottom: 12px; background: #111;
    }

    .rank-badge {
        position: absolute; top: -10px; left: 15px;
        background: var(--soft-pink); color: var(--bg-dark);
        padding: 5px 15px; border-radius: 8px;
        font-family: 'Unbounded'; font-size: 0.7rem; font-weight: 900;
        z-index: 2;
    }

    .borrow-stats {
        margin-top: 10px;
        background: rgba(162, 210, 255, 0.1); color: var(--baby-blue);
        padding: 6px 10px; border-radius: 8px;
        font-size: 0.7rem; font-weight: 700; text-align: center;
        border: 1px solid rgba(162, 210, 255, 0.2);
    }

    .book-title-small { font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
    .book-author-small { color: var(--text-subtle); font-size: 0.75rem; font-weight: 600; margin-bottom: 0; }

    .btn-nav-slider {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 45px; height: 45px; border-radius: 50%;
        background: rgba(20, 20, 25, 0.8); color: var(--lavender);
        border: 1px solid var(--glass-border); cursor: pointer;
        z-index: 100; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-nav-slider:hover { background: var(--lavender); color: var(--bg-dark); }
    .btn-prev { left: 15px; }
    .btn-next { right: 15px; }

    /* ============================================================
       5. KOLEKSI BUKU (GRID DATA REALTIME DB)
       =========================================================== */
    #koleksi { padding: 40px 60px 100px; position: relative; z-index: 10; }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 20px;
    }

    .book-item {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 12px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .book-item:hover {
        border-color: var(--baby-blue);
        box-shadow: 0 8px 20px rgba(162, 210, 255, 0.1);
        transform: translateY(-5px);
    }

    .img-box {
        width: 100%; height: 230px; 
        background: #000; border-radius: 8px; overflow: hidden;
        position: relative; margin-bottom: 12px;
    }

    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.08); }

    .category-pill {
        position: absolute; top: 8px; right: 8px;
        padding: 4px 8px; background: rgba(0,0,0,0.7);
        backdrop-filter: blur(5px); border-radius: 5px;
        font-size: 0.6rem; font-weight: 700;
        color: var(--lavender); border: 1px solid var(--lavender);
    }

    .book-info-container { display: flex; flex-direction: column; flex-grow: 1; }
    .b-title { 
        font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; 
        color: #fff; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .b-author { color: var(--text-subtle); font-size: 0.75rem; margin-bottom: 10px; font-weight: 500; }
    
    .b-meta {
        margin-top: auto; display: flex; flex-direction: column; gap: 5px;
        font-size: 0.75rem; padding-top: 10px;
        border-top: 1px dashed rgba(255,255,255,0.1);
    }

    .b-meta-row { display: flex; justify-content: space-between; align-items: center; }
    .b-year { color: var(--text-subtle); }
    .b-stock { font-weight: 800; color: var(--soft-pink); }

    /* Floating Back Button */
    .btn-kembali {
        position: fixed; bottom: 30px; right: 30px; 
        background: var(--lavender); color: var(--bg-dark);
        padding: 12px 25px; border-radius: 50px; 
        text-decoration: none !important; font-weight: 800; z-index: 1000;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 20px rgba(205, 180, 219, 0.2);
        transition: 0.3s; font-size: 0.85rem;
    }

    .btn-kembali:hover { transform: scale(1.05); background: #fff; }

    /* Pagination Styling */
    .pagination { margin-bottom: 0; }
    .page-item.active .page-link { background-color: var(--baby-blue); border-color: var(--baby-blue); color: var(--bg-dark); }
    .page-link { background-color: var(--glass); border-color: var(--glass-border); color: #fff; }
    .page-link:hover { background-color: rgba(255,255,255,0.1); color: var(--baby-blue); }

    /* ============================================================
       RESPONSIVE DESIGN
       =========================================================== */
    @media (max-width: 992px) {
        .catalog-nav { padding: 15px 30px; }
        .search-container { flex-direction: column; }
        .btn-cari { padding: 15px; }
    }

    @media (max-width: 768px) {
        #koleksi, .slider-wrapper { padding-left: 20px; padding-right: 20px; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .img-box { height: 190px; }
        .slider-card { min-width: 160px; max-width: 160px; }
        .slider-card img { height: 210px; }
        .hero-catalog h1 { font-size: 1.8rem; }
        .btn-nav-slider { display: none; } 
    }
</style>

<nav class="catalog-nav">
    <div class="brand-libwe">LIBWE</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="#top10">Populer</a>
        <a href="#koleksi" class="active">Katalog</a>
    </div>
</nav>

<section class="hero-catalog">
    <h1>KATALOG <span>BUKU</span></h1>
    <p>Eksplorasi Dunia Tanpa Batas</p>

    <div class="search-container">
        <input type="text" id="keyword" placeholder="Cari judul, penulis, atau penerbit..." value="{{ request('search') }}">
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

<section id="top10">
    <div class="section-title">BUKU TERPOPULER</div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider btn-prev" onclick="moveSlide(-210)"><i class="fas fa-chevron-left"></i></button>
        <button class="btn-nav-slider btn-next" onclick="moveSlide(210)"><i class="fas fa-chevron-right"></i></button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                
                <div class="img-box" style="margin-bottom: 12px; height:250px;">
                    @if($pb->gambar)
                        <img src="{{ asset('storage/'.$pb->gambar) }}" alt="{{ $pb->judul }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                    @else
                        <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default">
                    @endif
                </div>
                
                <h3 class="book-title-small">{{ Str::limit($pb->judul, 30) }}</h3>
                <p class="book-author-small"><i class="fas fa-pen-nib"></i> {{ $pb->penulis ?? 'Anonim' }}</p>
                
                <div class="borrow-stats">
                    <i class="fas fa-bookmark"></i> Dipinjam: {{ $pb->jumlah_dipinjam ?? 0 }}x
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

    <div class="d-flex justify-content-center" style="margin-top: 50px;">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>
</section>

<a href="{{ url('/') }}" class="btn-kembali">
    <i class="fas fa-arrow-left"></i> KEMBALI
</a>

<script>
    // Logika Slider
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // Logika Filter AJAX (Sesuai dengan KatalogController fungsi filter)
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
                                    <div class="b-meta-row">
                                        <span class="b-year"><i class="fas fa-calendar-alt"></i> ${tahun}</span>
                                        <span class="b-stock">Stok: ${stok}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--text-subtle);"><h3>Buku tidak ditemukan.</h3></div>';
                }

                container.innerHTML = html;
                container.style.opacity = '1';
                
                // Update URL parameter (opsional agar kalau di-refresh tetap sama)
                const url = new URL(window.location);
                if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error fetching data:', err);
                container.style.opacity = '1';
            });
    }

    // Eksekusi pencarian ketika klik tombol Enter pada keyboard
    document.getElementById('keyword').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterBuku();
        }
    });
</script>
@endsection