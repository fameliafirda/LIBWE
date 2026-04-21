@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* === SEMUA CSS KAMU TETAP (TIDAK DIUBAH) === */
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
.main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { display:none!important; }
.content-wrapper { margin-left:0!important; padding:0!important; background:var(--bg-main)!important; min-height:100vh; }
body { font-family:'Plus Jakarta Sans',sans-serif; background-color:var(--bg-main); color:#fff; overflow-x:hidden; }
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
        <input type="text" id="keyword" value="{{ request('search') }}">
        <select id="kat_id">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" onclick="filterBuku()">CARI</button>
    </div>
</section>

@if($popularBooks->count() > 0)
<div class="recommendation-wrapper" id="top10">
    <div class="section-title">BUKU TERPOPULER</div>

    <div class="track-container">
        @foreach($popularBooks as $pb)
        @php
            // 🔥 FIX DI SINI
            $imageUrlPopuler = $pb->cover ? asset('storage/'.$pb->cover) : asset('web-perpus/img/bukubaru.png');
        @endphp
        <div class="slider-card">
            <img src="{{ $imageUrlPopuler }}">
            <h3>{{ $pb->judul }}</h3>
        </div>
        @endforeach
    </div>
</div>
@endif

<section id="koleksi">
    <div class="book-grid" id="containerKoleksi">

        @foreach($books as $b)
        @php
            // 🔥 FIX DI SINI
            $imageUrl = $b->cover ? asset('storage/'.$b->cover) : asset('web-perpus/img/bukubaru.png');
        @endphp

        <div class="book-item">
            <img src="{{ $imageUrl }}">
            <h3>{{ $b->judul }}</h3>
        </div>
        @endforeach

    </div>
</section>

<script>
function filterBuku(){
    let keyword = document.getElementById('keyword').value;
    let kategori = document.getElementById('kat_id').value;

    fetch(`/katalog/filter?search=${keyword}&kategori=${kategori}`)
    .then(res=>res.json())
    .then(data=>{
        let html='';
        data.books.forEach(b=>{

            // 🔥 FIX DI SINI
            let img = b.cover ? `/storage/${b.cover}` : '/web-perpus/img/bukubaru.png';

            html+=`
            <div class="book-item">
                <img src="${img}">
                <h3>${b.judul}</h3>
            </div>`;
        });
        document.getElementById('containerKoleksi').innerHTML=html;
    });
}
</script>

@endsection