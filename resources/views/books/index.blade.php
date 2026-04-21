@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --bg-main: #050508; 
        --bg-section: #0f0f16;
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

    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(216, 180, 226, 0.05) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(155, 246, 255, 0.05) 0%, transparent 40%);
        z-index: 0;
        pointer-events: none;
    }

    .catalog-nav {
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        padding: 20px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(5, 5, 8, 0.8);
        backdrop-filter: blur(15px);
        z-index: 1000;
        border-bottom: 1px solid var(--glass-border);
    }

    .brand-libwe { font-family: 'Unbounded', sans-serif; font-size: 1.5rem; font-weight: 900; color: #fff; letter-spacing: 1px; }
    .brand-libwe span { color: var(--baby-blue); }

    .nav-links { display: flex; gap: 35px; align-items: center; }
    .nav-links a { 
        text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.85rem;
        text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--soft-pink); }
    
    /* Style untuk rekomendasi buku */
    .recommendation-wrapper {
        margin: 40px 40px 80px 40px; padding: 60px 0;
        background: linear-gradient(145deg, var(--bg-section), #0a0a0f);
        border-radius: 40px; border: 1px solid rgba(216, 180, 226, 0.1);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        position: relative; z-index: 10; overflow: hidden;
    }

    .section-title {
        text-align: center; margin-bottom: 40px; font-family: 'Unbounded', sans-serif;
        font-size: 1.8rem; color: #fff;
    }

    .slider-wrapper { position: relative; width: 100%; padding: 0 60px; }

    .track-container {
        display: flex; gap: 25px; overflow-x: auto; scroll-behavior: smooth; padding: 20px 10px; scrollbar-width: none;
    }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 180px; max-width: 180px; background: rgba(0, 0, 0, 0.4);
        border: 1px solid var(--glass-border); padding: 15px; border-radius: 20px;
        transition: 0.4s; position: relative; display: flex; flex-direction: column;
    }

    .slider-card img {
        width: 100%; height: 240px; object-fit: cover; border-radius: 12px; margin-bottom: 15px; background: #1a1a2e;
    }

    .rank-badge {
        position: absolute; top: -10px; left: 15px; background: var(--soft-pink); color: var(--bg-main);
        padding: 6px 15px; border-radius: 10px; font-family: 'Unbounded'; font-size: 0.7rem; font-weight: 900; z-index: 2;
    }

    .borrow-stats {
        position: absolute; top: 10px; right: 10px; background: rgba(5, 5, 8, 0.8); color: var(--baby-blue);
        backdrop-filter: blur(5px); padding: 5px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 700;
    }

    .book-title-small { font-family: 'Unbounded'; font-size: 0.85rem; margin-bottom: 5px; color: #fff; }
    .book-author-small { color: var(--text-muted); font-size: 0.75rem; }

    /* Style lainnya tetap... */
</style>

<!-- Hero Section -->
<section class="hero-catalog">
    <h1>KATALOG <span>BUKU</span></h1>
    <p>Eksplorasi Koleksi Perpustakaan</p>
</section>

<!-- Recomendation Section -->
@if($popularBooks->count() > 0)
<div class="recommendation-wrapper">
    <div class="section-title"><i class="fas fa-fire"></i> BUKU TERPOPULER</div>
    <div class="slider-wrapper">
        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                <div class="borrow-stats"><i class="fas fa-book-reader"></i> {{ $pb->total_dipinjam }}x</div>
                <div class="img-box">
                    @if($pb->cover)
                        <img src="{{ asset('storage/' . $pb->cover) }}" alt="{{ $pb->judul }}">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1a1a2e;">
                            <i class="fas fa-book" style="font-size: 3rem; color: var(--text-muted); opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>
                <h3 class="book-title-small">{{ Str::limit($pb->judul, 28) }}</h3>
                <p class="book-author-small">{{ $pb->penulis ?? 'Anonim' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Buku Koleksi -->
<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">
        @forelse($books as $b)
        <div class="book-item">
            <div class="img-box">
                <span class="category-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                @if($b->cover)
                    <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}">
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1a1a2e;">
                        <i class="fas fa-book" style="font-size: 2.5rem; color: var(--text-muted); opacity: 0.3;"></i>
                    </div>
                @endif
            </div>
            <div class="book-info-container">
                <h3 class="b-title">{{ Str::limit($b->judul, 40) }}</h3>
                <p class="b-author">{{ Str::limit($b->penulis ?? 'Anonim', 25) }}</p>
                <div class="b-meta">
                    <div class="b-meta-row">
                        <span class="b-year"><i class="fas fa-calendar-alt"></i> {{ $b->tahun_terbit ?? '-' }}</span>
                        <span class="b-stock"><i class="fas fa-box"></i> {{ $b->stok ?? 0 }}</span>
                        <span class="b-dipinjam"><i class="fas fa-book-reader"></i> {{ $b->total_dipinjam }}x</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px;">
            <i class="fas fa-book-open" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted);">Belum ada buku tersedia</h3>
        </div>
        @endforelse
    </div>

    @if($books->hasPages())
    <div class="d-flex justify-content-center">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>
    @endif
</section>

<a href="{{ url('/') }}" class="btn-kembali">
    <i class="fas fa-arrow-left"></i> KEMBALI
</a>

<script>
    function moveSlide(offset) {
        const slider = document.getElementById('mainSlider');
        if(slider) slider.scrollBy({ left: offset, behavior: 'smooth' });
    }

    function filterBuku() {
        let keyword = document.getElementById('keyword').value;
        let kategori = document.getElementById('kat_id').value;
        let container = document.getElementById('containerKoleksi');
        if(!container) return;
        
        container.style.opacity = '0.3';
        fetch(`{{ route('katalog.filter') }}?search=${encodeURIComponent(keyword)}&kategori=${encodeURIComponent(kategori)}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.success && data.books && data.books.length > 0) {
                    data.books.forEach(b => {
                        let hasCover = b.cover ? true : false;
                        let imgHtml = hasCover ? `<img src="/storage/${b.cover}" alt="${escapeHtml(b.judul)}">` : 
                            `<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1a1a2e;"><i class="fas fa-book" style="font-size: 2.5rem; color: #94a3b8; opacity: 0.3;"></i></div>`; 
                        
                        html += ` 
                        <div class="book-item">
                            <div class="img-box">
                                <span class="category-pill">${escapeHtml(b.kategori?.nama || 'Umum')}</span>
                                ${imgHtml}
                            </div>
                            <div class="book-info-container">
                                <h3 class="b-title">${escapeHtml(b.judul.length > 40 ? b.judul.substring(0,40) + '...' : b.judul)}</h3>
                                <p class="b-author">${escapeHtml(b.penulis ? (b.penulis.length > 25 ? b.penulis.substring(0,25) + '...' : b.penulis) : 'Anonim')}</p>
                                <div class="b-meta">
                                    <div class="b-meta-row">
                                        <span class="b-year"><i class="fas fa-calendar-alt"></i> ${escapeHtml(b.tahun_terbit || '-')}</span>
                                        <span class="b-stock"><i class="fas fa-box"></i> ${escapeHtml(b.stok ?? '0')}</span>
                                        <span class="b-dipinjam"><i class="fas fa-book-reader"></i> ${escapeHtml(b.total_dipinjam)}x</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;}
                    container.innerHTML = html;
                    container.style.opacity = '1';
                    const url = new URL(window.location);
                    if(keyword) url.searchParams.set('search', keyword); else url.searchParams.delete('search');
                    if(kategori) url.searchParams.set('kategori', kategori); else url.searchParams.delete('kategori');
                    window.history.pushState({}, '', url);
                })
                .catch(err => {
                    console.error('Error:', err);
                    container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 80px 20px;"><i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: var(--soft-pink); margin-bottom: 20px; display: block;"></i><h3 style="color: var(--text-muted);">Terjadi kesalahan</h3></div>';
                    container.style.opacity = '1';
                });
            }
</script>
@endsection