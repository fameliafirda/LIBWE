@extends('layouts.app')

@section('title', 'Manajemen Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --bg-main: #050508; 
        --bg-island: #0f0f16;
        --lavender: #d8b4e2;
        --soft-pink: #ffb3c6;
        --baby-blue: #9bf6ff;
        --glass-border: rgba(255, 255, 255, 0.08);
        --text-muted: rgba(255, 255, 255, 0.5);
    }

    /* Reset AdminLTE / Default Styles */
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

    .nav-actions { display: flex; gap: 15px; align-items: center; }
    .btn-tambah {
        background: var(--lavender); color: #000; padding: 10px 20px; border-radius: 50px;
        font-weight: 800; font-size: 0.8rem; text-decoration: none; transition: 0.3s;
    }
    .btn-tambah:hover { background: #fff; transform: scale(1.05); }

    /* Hero Search */
    .hero-catalog { padding: 140px 20px 40px; text-align: center; position: relative; z-index: 10; }
    .hero-catalog h1 { font-family: 'Unbounded', sans-serif; font-size: clamp(2rem, 5vw, 3.5rem); color: #fff; margin-bottom: 15px; }
    .hero-catalog h1 span { background: linear-gradient(to right, var(--lavender), var(--soft-pink)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    .search-container {
        display: flex; justify-content: center; gap: 15px; width: 100%; max-width: 850px; margin: 0 auto;
        background: rgba(255, 255, 255, 0.03); padding: 12px; border-radius: 20px; border: 1px solid var(--glass-border); backdrop-filter: blur(10px);
    }
    .search-container input, .search-container select {
        background: rgba(0, 0, 0, 0.4); border: 1px solid transparent; padding: 14px 20px; border-radius: 12px; color: #fff; outline: none; flex: 1;
    }
    .btn-cari { background: var(--lavender); color: #000; border: none; padding: 0 40px; border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s; font-family: 'Unbounded'; font-size: 0.9rem; }

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
        z-index: 100; transition: 0.3s;
    }
    .arrow-left { left: 0; } .arrow-right { right: 0; }

    .track-container { display: flex; gap: 25px; overflow-x: auto; scroll-behavior: smooth; padding: 15px 5px; scrollbar-width: none; }
    .track-container::-webkit-scrollbar { display: none; }

    .slider-card {
        min-width: 200px; max-width: 200px; background: rgba(0,0,0,0.5); border: 1px solid var(--glass-border);
        padding: 15px; border-radius: 20px; transition: 0.4s; position: relative;
    }
    .slider-card img { width: 100%; height: 260px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
    .borrow-count { position: absolute; top: 25px; right: 25px; background: var(--baby-blue); color: #000; padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; }

    /* KATALOG GRID */
    .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 30px; padding: 20px 60px 100px; }
    .book-item { background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); padding: 15px; border-radius: 22px; transition: 0.3s; position: relative; }
    .book-item:hover { border-color: var(--baby-blue); transform: translateY(-5px); }
    
    .img-box { width: 100%; height: 240px; border-radius: 15px; overflow: hidden; margin-bottom: 15px; position: relative; }
    .img-box img { width: 100%; height: 100%; object-fit: cover; }

    .b-title { font-family: 'Unbounded'; font-size: 0.9rem; margin-bottom: 10px; height: 2.8em; overflow: hidden; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    
    /* Tombol Aksi */
    .action-tools { display: flex; gap: 10px; margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 12px; }
    .btn-tool { flex: 1; padding: 8px; border-radius: 10px; text-align: center; font-size: 0.8rem; text-decoration: none !important; transition: 0.2s; }
    .btn-edit { background: rgba(155, 246, 255, 0.1); color: var(--baby-blue); border: 1px solid var(--baby-blue); }
    .btn-edit:hover { background: var(--baby-blue); color: #000; }
    .btn-delete { background: rgba(255, 179, 198, 0.1); color: var(--soft-pink); border: 1px solid var(--soft-pink); cursor: pointer; }
    .btn-delete:hover { background: var(--soft-pink); color: #000; }

    .btn-back { position: fixed; bottom: 30px; right: 30px; background: #fff; color: #000; padding: 15px 35px; border-radius: 50px; text-decoration: none !important; font-weight: 800; z-index: 1000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }

    @media (max-width: 768px) {
        .catalog-nav { padding: 15px 30px; }
        .book-grid { padding: 20px 20px; }
        .nav-arrow { display: none; }
    }
</style>

<nav class="catalog-nav">
    <a href="{{ url('/') }}" class="brand-libwe">LIB<span>WE</span></a>
    <div class="nav-actions">
        <a href="{{ url('/dashboard') }}" style="color: #fff; text-decoration: none; font-weight: 600; font-size: 0.8rem;">DASHBOARD</a>
        <a href="{{ route('books.create') }}" class="btn-tambah"><i class="fas fa-plus"></i> TAMBAH BUKU</a>
    </div>
</nav>

<section class="hero-catalog">
    <h1>MANAJEMEN <span>BUKU</span></h1>
    <div class="search-container">
        <input type="text" id="keyword" placeholder="Cari judul atau penulis...">
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
<div class="recommendation-island" id="top10">
    <div class="island-title">🔥 TERPOPULER</div>
    <div class="slider-wrapper">
        <button class="nav-arrow arrow-left" onclick="scrollSlider(-240)"><i class="fas fa-chevron-left"></i></button>
        <button class="nav-arrow arrow-right" onclick="scrollSlider(240)"><i class="fas fa-chevron-right"></i></button>
        <div class="track-container" id="mainSlider">
            @foreach($popularBooks as $index => $pb)
            <div class="slider-card">
                <div class="borrow-count">{{ $pb->total_dipinjam }}x Pinjam</div>
                @php $imgP = $pb->cover ? str_replace('public/', '', $pb->cover) : ''; @endphp
                <img src="{{ asset('storage/' . $imgP) }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
                <div class="b-title" style="height: auto; -webkit-line-clamp: 1;">{{ $pb->judul }}</div>
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
                @php $imgB = $b->cover ? str_replace('public/', '', $b->cover) : ''; @endphp
                <img src="{{ asset('storage/' . $imgB) }}" onerror="this.src='{{ asset('web-perpus/img/bukubaru.png') }}'">
            </div>
            <div class="b-title">{{ $b->judul }}</div>
            <div style="font-size: 0.75rem; color: var(--baby-blue); font-weight: 700;">Stok: {{ $b->stok }}</div>
            
            <div class="action-tools">
                <a href="{{ route('books.edit', $b->id) }}" class="btn-tool btn-edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('books.destroy', $b->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-tool btn-delete" style="width: 100%;"><i class="fas fa-trash"></i></button>
                </form>
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
    function scrollSlider(offset) {
        document.getElementById('mainSlider').scrollBy({ left: offset, behavior: 'smooth' });
    }

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
                        let cleanImg = b.cover ? b.cover.replace('public/', '') : '';
                        let img = cleanImg ? `/storage/${cleanImg}` : '{{ asset("web-perpus/img/bukubaru.png") }}';
                        let kname = b.kategori ? b.kategori.nama : 'Umum';
                        
                        html += `
                        <div class="book-item">
                            <div class="img-box">
                                <span class="category-label">${kname}</span>
                                <img src="${img}" onerror="this.src='{{ asset("web-perpus/img/bukubaru.png") }}'">
                            </div>
                            <div class="b-title">${b.judul}</div>
                            <div style="font-size: 0.75rem; color: var(--baby-blue); font-weight: 700;">Stok: ${b.stok}</div>
                            <div class="action-tools">
                                <a href="/books/${b.id}/edit" class="btn-tool btn-edit"><i class="fas fa-edit"></i></a>
                                <form action="/books/${b.id}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus buku ini?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-tool btn-delete" style="width: 100%;"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div style="grid-column: 1/-1; text-align: center; padding: 60px;"><h3>Buku tidak ditemukan</h3></div>';
                }
                container.innerHTML = html;
                container.style.opacity = '1';
            });
    }

    document.getElementById('keyword').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') filterBuku();
    });
</script>
@endsection