@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* (SEMUA CSS KAMU TETAP, AKU TIDAK UBAH SAMA SEKALI) */
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
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">
            <i class="fas fa-search"></i> CARI
        </button>
    </div>
</section>

@if($popularBooks->count() > 0)
<div class="recommendation-wrapper" id="top10">
    <div class="section-title"><i class="fas fa-fire"></i> BUKU TERPOPULER</div>

    <div class="slider-wrapper">
        <button class="btn-nav-slider btn-prev" onclick="moveSlide(-200)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="btn-nav-slider btn-next" onclick="moveSlide(200)">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                <div class="borrow-stats">
                    <i class="fas fa-book-reader"></i> {{ $pb->total_dipinjam ?? 0 }}x
                </div>

                @php
                    // ✅ FIX: pakai cover
                    $imageUrlPopuler = $pb->cover ? asset('storage/' . $pb->cover) : asset('web-perpus/img/bukubaru.png');
                @endphp

                <div class="img-box" style="height: 240px; margin-bottom: 10px;">
                    <img src="{{ $imageUrlPopuler }}" alt="{{ $pb->judul }}"
                         onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                </div>

                <h3 class="book-title-small">{{ Str::limit($pb->judul, 28) }}</h3>
                <p class="book-author-small">{{ $pb->penulis ?? 'Anonim' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">
        @forelse($books as $b)
        <div class="book-item">

            @php
                // ✅ FIX: pakai cover
                $imageUrl = $b->cover ? asset('storage/' . $b->cover) : asset('web-perpus/img/bukubaru.png');
            @endphp

            <div class="img-box">
                <span class="category-pill">{{ $b->kategori->nama ?? 'Umum' }}</span>
                <img src="{{ $imageUrl }}" alt="{{ $b->judul }}"
                     onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
            </div>

            <div class="book-info-container">
                <h3 class="b-title">{{ Str::limit($b->judul, 40) }}</h3>
                <p class="b-author">{{ Str::limit($b->penulis ?? 'Anonim', 25) }}</p>

                <div class="b-meta">
                    <div class="b-meta-row">
                        <span class="b-year">
                            <i class="fas fa-calendar-alt"></i> {{ $b->tahun_terbit ?? '-' }}
                        </span>
                        <span class="b-stock">
                            <i class="fas fa-box"></i> {{ $b->stok ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px;">
            <h3 style="color: var(--text-muted);">Belum ada buku tersedia</h3>
        </div>
        @endforelse
    </div>

    @if($books->hasPages())
    <div class="d-flex justify-content-center" style="margin-top: 60px;">
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
    if (slider) {
        slider.scrollBy({ left: offset, behavior: 'smooth' });
    }
}

function filterBuku() {
    let keyword = document.getElementById('keyword').value;
    let kategori = document.getElementById('kat_id').value;
    let container = document.getElementById('containerKoleksi');

    container.style.opacity = '0.3';

    fetch(`{{ route('katalog.filter') }}?search=${encodeURIComponent(keyword)}&kategori=${encodeURIComponent(kategori)}`)
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (data.success && data.books.length > 0) {
                data.books.forEach(b => {

                    // ✅ FIX: pakai cover
                    let img = b.cover ? `/storage/${b.cover}` : '{{ asset("web-perpus/img/bukubaru.png") }}';

                    html += `
                    <div class="book-item">
                        <div class="img-box">
                            <img src="${img}" onerror="this.src='{{ asset("web-perpus/img/bukubaru.png") }}'">
                        </div>
                        <div class="book-info-container">
                            <h3 class="b-title">${b.judul}</h3>
                            <p class="b-author">${b.penulis ?? 'Anonim'}</p>
                        </div>
                    </div>`;
                });
            } else {
                html = `<div style="text-align:center;">Buku tidak ditemukan</div>`;
            }

            container.innerHTML = html;
            container.style.opacity = '1';
        });
}
</script>

@endsection