@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       Y2K SOFT AESTHETIC STYLE
       ============================================================ */
    :root {
        --bg-main: #050508; 
        --bg-island: #0f0f16;
        --lavender: #d8b4e2;
        --soft-pink: #ffb3c6;
        --baby-blue: #9bf6ff;
        --glass-border: rgba(255, 255, 255, 0.08);
        --text-muted: rgba(255, 255, 255, 0.5);
    }

    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: var(--bg-main) !important; min-height: 100vh; }
    
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: #ffffff; overflow-x: hidden; scroll-behavior: smooth; }

    /* Navigasi */
    .catalog-nav {
        position: fixed; top: 0; left: 0; width: 100%; padding: 20px 60px; display: flex; justify-content: space-between; align-items: center;
        background: rgba(5, 5, 8, 0.85); backdrop-filter: blur(15px); z-index: 1000; border-bottom: 1px solid var(--glass-border);
    }
    .brand-libwe { font-family: 'Unbounded', sans-serif; font-size: 1.5rem; font-weight: 900; color: #fff; text-decoration: none; }
    .brand-libwe span { color: var(--baby-blue); }

    /* Hero Search */
    .hero-catalog { padding: 140px 20px 40px; text-align: center; position: relative; z-index: 10; }
    .hero-catalog h1 { font-family: 'Unbounded', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem); color: #fff; margin-bottom: 15px; }
    .hero-catalog h1 span { background: linear-gradient(to right, var(--lavender), var(--soft-pink)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    .search-container {
        display: flex; justify-content: center; gap: 15px; width: 100%; max-width: 850px; margin: 0 auto;
        background: rgba(255, 255, 255, 0.03); padding: 12px; border-radius: 20px; border: 1px solid var(--glass-border); backdrop-filter: blur(10px);
    }
    .search-container input, .search-container select {
        background: rgba(0, 0, 0, 0.4); border: 1px solid transparent; padding: 14px 20px; border-radius: 12px; color: #fff; outline: none; flex: 1; font-family: inherit;
    }
    .btn-cari { background: var(--lavender); color: #000; border: none; padding: 0 40px; border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s; font-family: 'Unbounded'; font-size: 0.9rem; }
    .btn-cari:hover { background: #fff; transform: translateY(-2px); }

    /* RECOMMENDATION ISLAND */
    .recommendation-island {
        margin: 40px; padding: 50px 20px; background: var(--bg-island); border-radius: 40px;
        border: 1px solid rgba(216, 180, 226, 0.15); position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }
    .island-title { text-align: center; font-family: 'Unbounded'; font-size: 1.8rem; margin-bottom: 40px; color: #fff; letter-spacing: 2px; }

    .slider-wrapper { position: relative; width: 100%; padding: 0 60px; }
    
    .nav-arrow {
        position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px;
        background: var(--lavender); color: #000; border-radius: 50%; border: none;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        z-index: 100; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .nav-arrow:hover { background: #fff; transform: translateY(-50%) scale(1.1); }
    .arrow-left { left: 0; }
    .arrow-right { right: 0; }

    .track-container {
        display: flex; gap: 25px; overflow-x: auto; scroll-behavior: smooth; padding: 15px 5px; scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 200px; max-width: 200px; background: rgba(0,0,0,0.5); border: 1px solid var(--glass-border);
        padding: 15px; border-radius: 20px; transition: 0.4s; position: relative;
    }
    .slider-card:hover { border-color: var(--soft-pink); transform: translateY(-10px); }
    .slider-card img { width: 100%; height: 260px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; background: #000; }

    .rank-badge { position: absolute; top: 25px; left: 25px; background: var(--soft-pink); color: #000; padding: 5px 15px; border-radius: 10px; font-weight: 900; font-size: 0.8rem; z-index: 10; }
    .borrow-count { position: absolute; top: 25px; right: 25px; background: rgba(0,0,0,0.7); color: var(--baby-blue); padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; border: 1px solid var(--baby-blue); }

    /* KATALOG GRID */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 30px; padding: 20px 60px 100px; }
    .book-item { background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); padding: 15px; border-radius: 22px; transition: 0.3s; position: relative; }
    .book-item:hover { border-color: var(--baby-blue); transform: translateY(-5px); }
    
    .img-box { width: 100%; height: 240px; border-radius: 15px; overflow: hidden; margin-bottom: 15px; position: relative; }
    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-item:hover .img-box img { transform: scale(1.05); }

    .category-label { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: var(--lavender); padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; border: 1px solid var(--lavender); backdrop-filter: blur(5px); }

    .b-title { font-family: 'Unbounded'; font-size: 0.95rem; margin-bottom: 10px; height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; }
    .b-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 12px; }

    .btn-back {
        position: fixed; bottom: 30px; right: 30px; background: #fff; color: #000;
        padding: 15px 35px; border-radius: 50px; text-decoration: none !important; font-weight: 800; z-index: 1000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); transition: 0.3s;
    }
    .btn-back:hover { background: var(--lavender); transform: scale(1.05); }

    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 30px; }
        .search-container { flex-direction: column; }
        .book-grid { padding: 20px 20px; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px; }
        .recommendation-island { margin: 20px 10px; border-radius: 30px; }
        .slider-wrapper { padding: 0; }
        .nav-arrow { display: none; }
    }
</style>

<nav class="catalog-nav">
    <a href="{{ url('/') }}" class="brand-libwe">LIB<span>WE</span></a>
    <div class="d-none d-md-flex" style="gap: 30px;">
        <a href="{{ url('/dashboard') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 600;">Dashboard</a>
        <a href="#koleksi" style="text-decoration: none; color: var(--soft-pink); font-weight: 700;">Manajemen Buku</a>
    </div>
</nav>

<section class="hero-catalog">
    <h1>KATALOG <span>BUKU</span></h1>
    <p class="text-muted">Kelola koleksi pengetahuan perpustakaan</p>

    <div class="search-container">
        <input type="text" id="keyword" placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">CARI</button>
    </div>
</section>

@if($popularBooks->count() > 0)
<div class="recommendation-island" id="top10">
    <div class="island-title">🔥 TERPOPULER</div>
    <div class="slider-wrapper">
        <button class="nav-arrow arrow-left" onclick="scrollSlider(-240)"><i class="fas fa-chevron-left"></i></button>
        <button class="nav-arrow arrow-right" onclick="scrollSlider(240)"><i class="fas fa-chevron-right"></i></button>
        
        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                <div class="borrow-count">{{ $pb->total_dipinjam }}x Pinjam</div>
                
                @php $imgP = $pb->gambar ? str_replace('public/', '', $pb->gambar) : ''; @endphp
                <img src="{{ asset('storage/' . $imgP) }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                
                <div class="b-title" style="height: auto; -webkit-line-clamp: 1; margin-bottom: 5px;">{{ $pb->judul }}</div>
                <small class="text-muted">{{ $pb->penulis ?? 'Anonim' }}</small>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">
        @foreach($books as $b)
        <div class="book-item">
            <div class="img-box">
                <span class="category-label">{{ $b->kategori->nama ?? 'Umum' }}</span>
                @php $imgB = $b->gambar ? str_replace('public/', '', $b->gambar) : ''; @endphp
                <img src="{{ asset('storage/' . $imgB) }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
            </div>
            <div class="b-title">{{ $b->judul }}</div>
            <div class="b-meta">
                <span class="text-muted">{{ $b->tahun_terbit ?? '-' }}</span>
                <span style="color: var(--baby-blue); font-weight: 800;">Stok: {{ $b->stok }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4 mb-5">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>
</section>

<a href="{{ url('/dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> DASHBOARD</a>

<script>
    // Slider Logic
    function scrollSlider(offset) {
        document.getElementById('mainSlider').scrollBy({ left: offset, behavior: 'smooth' });
    }

    // AJAX Search & Filter
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');

        container.style.opacity = '0.3';

        fetch(`{{ route('books.filter') }}?search=${keyword}&kategori=${kategori}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.success && data.books.length > 0) {
                    data.books.forEach(b => {
                        let cleanImg = b.gambar ? b.gambar.replace('public/', '') : '';
                        let img = cleanImg ? `/storage/${cleanImg}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                        let kname = b.kategori ? b.kategori.nama : 'Umum';
                        
                        html += `
                        <div class="book-item">
                            <div class="img-box">
                                <span class="category-label">${kname}</span>
                                <img src="${img}" onerror="this.src='{{ asset("web-perpus/img/bukubaru.png") }}'">
                            </div>
                            <div class="b-title">${b.judul}</div>
                            <div class="b-meta">
                                <span class="text-muted">${b.tahun_terbit || '-'}</span>
                                <span style="color: var(--baby-blue); font-weight: 800;">Stok: ${b.stok}</span>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div style="grid-column: 1/-1; text-align: center; padding: 60px; color: var(--text-muted);"><h3>Buku tidak ditemukan</h3></div>';
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
        if (e.key === 'Enter') filterBuku();
    });
</script>
@endsection