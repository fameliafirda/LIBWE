@extends('layouts.app')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================================================
       1. COLOR PALETTE & RESET (MODERN LIGHT THEME)
       ============================================================ */
    :root {
        --primary: #6c5ce7;
        --secondary: #a29bfe;
        --accent: #fd79a8;
        --success: #00b894;
        --warning: #fdcb6e;
        --bg-body: #f8f9fa;
        --text-dark: #2d3436;
        --text-muted: #636e72;
    }

    /* Menyembunyikan elemen bawaan dari layout admin jika ada */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: var(--bg-body) !important; min-height: 100vh; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-dark);
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* ============================================================
       2. NAVIGASI
       =========================================================== */
    .catalog-nav {
        position: fixed; top: 0; left: 0; width: 100%;
        padding: 15px 50px; display: flex; justify-content: space-between; align-items: center;
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);
        z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .brand-logo { font-family: 'Poppins', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--primary); text-decoration: none; }
    .brand-logo span { color: var(--accent); }
    .nav-links a { 
        text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.95rem;
        margin-left: 30px; transition: 0.3s;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--primary); }

    /* ============================================================
       3. HERO SECTION (GRADIENT)
       =========================================================== */
    .hero-section {
        background: linear-gradient(135deg, #6c5ce7 0%, #fd79a8 100%);
        padding: 140px 20px 100px;
        text-align: center;
        border-radius: 0 0 50px 50px;
        position: relative;
    }
    .hero-section::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.15) 0%, transparent 40%),
                          radial-gradient(circle at 80% 70%, rgba(255,255,255,0.15) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-title { font-family: 'Poppins', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; color: white; margin-bottom: 10px; text-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .hero-subtitle { color: rgba(255,255,255,0.9); font-size: 1.1rem; font-weight: 500; margin-bottom: 0; }

    /* ============================================================
       4. SEARCH BAR (FLOATING GLASS)
       =========================================================== */
    .search-wrapper {
        max-width: 900px; margin: -40px auto 50px; position: relative; z-index: 10; padding: 0 20px;
    }
    .search-glass {
        background: white; border-radius: 50px; padding: 10px 15px; display: flex; gap: 10px;
        box-shadow: 0 15px 35px rgba(108, 92, 231, 0.15); align-items: center; border: 2px solid rgba(255,255,255,0.8);
    }
    .search-glass input, .search-glass select {
        border: none; background: transparent; padding: 12px 20px; font-size: 1rem; color: var(--text-dark);
        font-family: 'Plus Jakarta Sans'; font-weight: 500; outline: none; border-radius: 40px;
    }
    .search-glass input { flex: 2; }
    .search-glass select { flex: 1; border-left: 2px solid #f1f2f6; border-radius: 0; }
    .search-glass input:focus, .search-glass select:focus { background: #f8f9fa; }
    .btn-search {
        background: var(--primary); color: white; border: none; padding: 12px 35px; border-radius: 40px;
        font-weight: 700; cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif; letter-spacing: 1px;
    }
    .btn-search:hover { background: #5a4bcf; transform: scale(1.05); box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3); }

    /* ============================================================
       5. TOP 10 REKOMENDASI (HORIZONTAL SCROLL)
       =========================================================== */
    .section-container { padding: 0 50px 60px; max-width: 1400px; margin: 0 auto; }
    .section-title { font-family: 'Poppins', sans-serif; font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    
    .recommendation-bg {
        background: white; border-radius: 30px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 50px;
        border: 1px solid #f1f2f6;
    }

    .slider-container { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 20px; scroll-behavior: smooth; scrollbar-width: none; }
    .slider-container::-webkit-scrollbar { display: none; }

    .book-card-top {
        min-width: 200px; max-width: 200px; background: white; border-radius: 20px; padding: 15px;
        border: 1px solid #f1f2f6; position: relative; transition: 0.4s; cursor: pointer;
    }
    .book-card-top:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(253, 121, 168, 0.15); border-color: var(--accent); }

    .rank-badge {
        position: absolute; top: -12px; left: 15px; background: linear-gradient(135deg, var(--warning), #f39c12); color: white;
        width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-family: 'Poppins'; font-weight: 800; font-size: 1rem; z-index: 2; box-shadow: 0 4px 10px rgba(253, 203, 110, 0.4);
    }
    .borrow-badge {
        position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px);
        color: var(--primary); padding: 4px 10px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; z-index: 2; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .book-cover { width: 100%; height: 260px; object-fit: cover; border-radius: 12px; margin-bottom: 15px; background: #f8f9fa; }
    .book-title { font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 700; line-height: 1.3; color: var(--text-dark); margin-bottom: 5px; }
    .book-author { color: var(--text-muted); font-size: 0.8rem; font-weight: 500; }

    /* ============================================================
       6. KOLEKSI BUKU (GRID)
       =========================================================== */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; }

    .book-card {
        background: white; border-radius: 20px; overflow: hidden; transition: 0.4s;
        border: 1px solid #f1f2f6; display: flex; flex-direction: column; height: 100%;
    }
    .book-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(108, 92, 231, 0.1); border-color: var(--secondary); }

    .card-img-wrapper { position: relative; height: 280px; width: 100%; overflow: hidden; background: #f8f9fa; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-card:hover .card-img-wrapper img { transform: scale(1.05); }

    .cat-pill {
        position: absolute; bottom: 10px; left: 10px; background: var(--primary); color: white;
        padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; z-index: 2;
    }

    .card-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .c-title { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; line-height: 1.3; }
    .c-author { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 15px; }
    
    .c-footer { margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px dashed #f1f2f6; }
    .c-year { color: var(--text-muted); font-size: 0.8rem; font-weight: 600; }
    .c-stock { font-size: 0.8rem; font-weight: 800; padding: 5px 12px; border-radius: 10px; }
    .stock-in { background: #e8f8f5; color: var(--success); }
    .stock-out { background: #ff767520; color: #ff7675; }

    /* Navigasi Slider */
    .slider-nav-btn {
        position: absolute; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border-radius: 50%;
        background: white; color: var(--primary); border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); cursor: pointer;
        z-index: 10; display: flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 1.2rem;
    }
    .slider-nav-btn:hover { background: var(--primary); color: white; }
    .btn-prev { left: 10px; }
    .btn-next { right: 10px; }
    .slider-wrapper-rel { position: relative; }

    /* Float Button */
    .btn-float-back {
        position: fixed; bottom: 30px; right: 30px; background: white; color: var(--text-dark);
        padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 800; font-family: 'Poppins';
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: 0.3s; display: flex; align-items: center; gap: 10px; z-index: 1000;
    }
    .btn-float-back:hover { transform: translateY(-5px); background: var(--primary); color: white; }

    /* Pagination */
    .pagination { justify-content: center; margin-top: 50px; gap: 5px; }
    .page-item .page-link { border: none; color: var(--text-muted); font-weight: 600; border-radius: 10px !important; padding: 10px 18px; margin: 0 2px; }
    .page-item.active .page-link { background: var(--primary); color: white; box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }

    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 20px; }
        .hero-title { font-size: 2rem; }
        .search-glass { flex-direction: column; border-radius: 25px; padding: 20px; }
        .search-glass select { border-left: none; border-top: 2px solid #f1f2f6; width: 100%; }
        .btn-search { width: 100%; }
        .section-container { padding: 0 20px 40px; }
        .recommendation-bg { padding: 20px; }
        .slider-nav-btn { display: none; }
        .book-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
        .card-img-wrapper { height: 200px; }
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
    <h1 class="hero-title">Eksplorasi Dunia Buku</h1>
    <p class="hero-subtitle">Temukan ribuan ilmu dan cerita menarik di Perpustakaan SDN Berat Wetan 1</p>
</section>

<div class="search-wrapper">
    <div class="search-glass">
        <i class="fas fa-search text-muted ms-2 d-none d-md-block"></i>
        <input type="text" id="keyword" placeholder="Cari judul buku atau nama penulis..." value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-search" onclick="filterBuku()">CARI BUKU</button>
    </div>
</div>

<div class="section-container">
    @if($popularBooks->count() > 0)
    <div class="recommendation-bg" id="rekomendasi">
        <h2 class="section-title">
            <i class="fas fa-crown" style="color: var(--warning);"></i> Buku Paling Favorit
        </h2>
        <div class="slider-wrapper-rel">
            <button class="slider-nav-btn btn-prev" onclick="moveSlide(-250)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-nav-btn btn-next" onclick="moveSlide(250)"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-container" id="mainSlider">
                @foreach($popularBooks as $index => $pb)
                <div class="book-card-top">
                    <div class="rank-badge">#{{ $index + 1 }}</div>
                    <div class="borrow-badge"><i class="fas fa-fire text-danger me-1"></i> {{ $pb->total_dipinjam ?? 0 }}x Dipinjam</div>
                    
                    @if($pb->gambar)
                        <img src="{{ asset($pb->gambar) }}" alt="{{ $pb->judul }}" class="book-cover">
                    @else
                        <div class="book-cover d-flex align-items-center justify-content-center">
                            <i class="fas fa-book fa-3x" style="color: #dfe6e9;"></i>
                        </div>
                    @endif
                    
                    <h3 class="book-title" title="{{ $pb->judul }}">{{ Str::limit($pb->judul, 35) }}</h3>
                    <p class="book-author"><i class="fas fa-pen-nib me-1"></i> {{ Str::limit($pb->penulis ?? 'Anonim', 20) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div id="koleksi">
        <h2 class="section-title"><i class="fas fa-layer-group" style="color: var(--primary);"></i> Semua Koleksi Buku</h2>
        
        <div class="book-grid" id="containerKoleksi">
            @forelse($books as $b)
            <div class="book-card">
                <div class="card-img-wrapper">
                    <span class="cat-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                    @if($b->gambar)
                        <img src="{{ asset($b->gambar) }}" alt="{{ $b->judul }}">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: #f1f2f6;">
                            <i class="fas fa-book-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                            <span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600;">No Cover</span>
                        </div>
                    @endif
                </div>
                <div class="card-info">
                    <h3 class="c-title">{{ Str::limit($b->judul, 45) }}</h3>
                    <p class="c-author"><i class="fas fa-user-edit me-1 opacity-75"></i> {{ Str::limit($b->penulis ?? 'Anonim', 25) }}</p>
                    
                    <div class="c-footer">
                        <span class="c-year"><i class="far fa-calendar-alt me-1"></i> {{ $b->tahun_terbit ?? '-' }}</span>
                        @if($b->stok > 0)
                            <span class="c-stock stock-in"><i class="fas fa-check me-1"></i> Tersedia: {{ $b->stok }}</span>
                        @else
                            <span class="c-stock stock-out"><i class="fas fa-times me-1"></i> Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; background: white; border-radius: 30px;">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Not found" style="width: 120px; opacity: 0.5; margin-bottom: 20px;">
                <h3 style="font-family: 'Poppins'; color: var(--text-dark);">Buku Tidak Ditemukan</h3>
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
    <i class="fas fa-arrow-left"></i> KEMBALI
</a>

<script>
    // Fitur geser slider Top 10
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        if(slider) slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    // Fitur pencarian AJAX
    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');
        if(!container) return;
        
        container.style.opacity = '0.4';
        fetch(`{{ route('katalog.filter') }}?search=${encodeURIComponent(keyword)}&kategori=${encodeURIComponent(kategori)}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.success && data.books && data.books.length > 0) {
                    data.books.forEach(b => {
                        let hasCover = b.gambar ? true : false;
                        let imgPath = hasCover ? `/${b.gambar}` : '';
                        
                        // Menangani gambar atau placeholder
                        let imgHtml = hasCover 
                            ? `<img src="${imgPath}" alt="${escapeHtml(b.judul)}">` 
                            : `<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: #f1f2f6;"><i class="fas fa-book-open fa-3x mb-2" style="color: #cbd5e1;"></i><span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600;">No Cover</span></div>`;
                        
                        // Menangani status stok
                        let stockClass = b.stok > 0 ? 'stock-in' : 'stock-out';
                        let stockIcon = b.stok > 0 ? '<i class="fas fa-check me-1"></i> Tersedia: ' : '<i class="fas fa-times me-1"></i> Habis';
                        let stockText = b.stok > 0 ? b.stok : '';

                        html += `
                        <div class="book-card">
                            <div class="card-img-wrapper">
                                <span class="cat-pill">${escapeHtml(b.kategori?.nama || 'Umum')}</span>
                                ${imgHtml}
                            </div>
                            <div class="card-info">
                                <h3 class="c-title">${escapeHtml(b.judul.length > 45 ? b.judul.substring(0,45) + '...' : b.judul)}</h3>
                                <p class="c-author"><i class="fas fa-user-edit me-1 opacity-75"></i> ${escapeHtml(b.penulis ? (b.penulis.length > 25 ? b.penulis.substring(0,25) + '...' : b.penulis) : 'Anonim')}</p>
                                <div class="c-footer">
                                    <span class="c-year"><i class="far fa-calendar-alt me-1"></i> ${escapeHtml(b.tahun_terbit || '-')}</span>
                                    <span class="c-stock ${stockClass}">${stockIcon}${stockText}</span>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; background: white; border-radius: 30px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Not found" style="width: 120px; opacity: 0.5; margin-bottom: 20px;">
                        <h3 style="font-family: 'Poppins'; color: var(--text-dark);">Buku Tidak Ditemukan</h3>
                        <p style="color: var(--text-muted);">Coba gunakan kata kunci atau kategori yang berbeda.</p>
                    </div>`;
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
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px;"><h3 style="color: var(--accent);">Terjadi kesalahan koneksi</h3></div>';
                container.style.opacity = '1';
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