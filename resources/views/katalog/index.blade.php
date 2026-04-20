@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       1. RESET & BASE THEME (FUTURISTIC DARK)
       ============================================================ */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: #02020a !important; 
        min-height: 100vh;
    }

    :root {
        --neon-pink: #ff00ff;
        --neon-purple: #bc13fe;
        --neon-blue: #00f2ff;
        --deep-space: #02020a;
        --glass-white: rgba(255, 255, 255, 0.08);
    }

    body {
        background: var(--deep-space);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow-x: hidden;
    }

    /* Animasi Latar Belakang Cahaya Bergerak */
    .nebula-bg {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 0, 255, 0.1), transparent 40%),
            radial-gradient(circle at 80% 70%, rgba(0, 242, 255, 0.1), transparent 40%);
        z-index: 1;
        pointer-events: none;
    }

    /* ============================================================
       2. STICKY GLASS NAVIGATION BAR
       =========================================================== */
    .navbar-glass {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: rgba(2, 2, 10, 0.7);
        backdrop-filter: blur(25px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 0;
        transition: 0.4s;
    }

    .nav-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-section h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(to right, var(--neon-blue), var(--neon-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .brand-section span {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.5);
    }

    /* Search & Filter Group */
    .action-group {
        display: flex;
        gap: 15px;
        align-items: center;
        flex: 0.6;
    }

    .search-box-cyber {
        flex: 1;
        background: var(--glass-white);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        display: flex;
        align-items: center;
        padding: 5px 5px 5px 20px;
        transition: 0.3s;
    }

    .search-box-cyber:focus-within {
        border-color: var(--neon-blue);
        box-shadow: 0 0 15px rgba(0, 242, 255, 0.3);
    }

    .search-box-cyber input {
        background: transparent;
        border: none;
        color: white;
        width: 100%;
        outline: none;
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .btn-glow {
        background: linear-gradient(45deg, var(--neon-purple), var(--neon-pink));
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--neon-pink);
    }

    .select-cyber {
        background: var(--glass-white);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding: 12px 20px;
        border-radius: 15px;
        outline: none;
        cursor: pointer;
    }

    /* ============================================================
       3. TRENDING SECTION (HORIZON SLIDER)
       =========================================================== */
    .section-title {
        margin: 40px 0 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-title h2 { font-size: 1.4rem; font-weight: 800; color: var(--neon-blue); margin: 0; }
    .title-line { height: 2px; flex: 1; background: linear-gradient(to right, var(--neon-blue), transparent); opacity: 0.3; }

    .trending-wrapper {
        display: flex;
        gap: 25px;
        overflow-x: auto;
        padding: 20px 0 40px;
        scrollbar-width: none;
    }

    .trending-wrapper::-webkit-scrollbar { display: none; }

    .card-popular {
        min-width: 200px;
        height: 300px;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.5s;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-popular:hover {
        transform: scale(1.05) rotate(2deg);
        border-color: var(--neon-pink);
        box-shadow: 0 10px 30px rgba(255, 0, 255, 0.3);
    }

    .card-popular img { width: 100%; height: 100%; object-fit: cover; }

    .rank-number {
        position: absolute;
        bottom: 15px; left: 15px;
        font-size: 4rem;
        font-weight: 900;
        line-height: 0.8;
        color: rgba(255, 255, 255, 0.2);
        -webkit-text-stroke: 1px rgba(255,255,255,0.5);
    }

    /* ============================================================
       4. MAIN GRID (KATALOG BUKU)
       =========================================================== */
    .main-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 30px;
        padding-bottom: 100px;
    }

    .book-card {
        background: var(--glass-white);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 15px;
        transition: 0.4s;
        position: relative;
        animation: fadeInUp 0.8s ease backwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .book-card:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: var(--neon-blue);
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .img-wrapper {
        width: 100%;
        height: 280px;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 15px;
        position: relative;
    }

    .img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .book-card:hover .img-wrapper img { transform: scale(1.1); }

    .category-tag {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        color: var(--neon-blue);
        font-size: 0.7rem;
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 700;
        border: 1px solid var(--neon-blue);
    }

    .book-info h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 5px; color: #fff; }
    .book-info p { font-size: 0.85rem; color: rgba(255,255,255,0.5); margin-bottom: 15px; }

    .stock-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 12px;
    }

    .stock-badge { color: var(--neon-pink); font-weight: 800; font-size: 0.8rem; }

</style>

<div class="nebula-bg"></div>

<nav class="navbar-glass">
    <div class="nav-container">
        <div class="brand-section">
            <h1>KATALOG BUKU</h1>
            <span>Perpustakaan SDN Berat Wetan 1</span>
        </div>

        <div class="action-group">
            <div class="search-box-cyber">
                <i class="fas fa-search" style="color: var(--neon-blue)"></i>
                <input type="text" id="inputCari" placeholder="Cari judul buku atau penulis...">
                <button class="btn-glow" onclick="prosesCari()">TEMUKAN</button>
            </div>

            <select id="filterKat" class="select-cyber" onchange="prosesCari()">
                <option value="">SEMUA KATEGORI</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</nav>

<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 0 30px; position: relative; z-index: 10;">
    
    @if($popularBooks->count() > 0)
    <div class="section-title">
        <i class="fas fa-fire-alt" style="color: var(--neon-pink)"></i>
        <h2>BUKU PALING SERU</h2>
        <div class="title-line"></div>
    </div>

    <div class="trending-wrapper">
        @foreach($popularBooks as $index => $pb)
        <div class="card-popular">
            <div class="rank-number">{{ $index + 1 }}</div>
            @if($pb->gambar)
                <img src="{{ asset('storage/'.$pb->gambar) }}" alt="">
            @else
                <div style="width:100%; height:100%; background:#1a1a2e; display:flex; align-items:center; justify-content:center">
                    <i class="fas fa-book fa-3x text-muted"></i>
                </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div class="section-title">
        <i class="fas fa-th-large" style="color: var(--neon-blue)"></i>
        <h2>JELAJAHI SEMUA BUKU</h2>
        <div class="title-line"></div>
    </div>

    <div id="gridBuku" class="main-grid">
        @foreach($books as $key => $book)
        <div class="book-card" style="animation-delay: {{ $key * 0.1 }}s">
            <div class="img-wrapper">
                <span class="category-tag">{{ $book->kategori->nama ?? 'Umum' }}</span>
                @if($book->gambar)
                    <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                @else
                    <div style="height:100%; background:#1a1a2e; display:flex; align-items:center; justify-content:center">
                        <i class="fas fa-image fa-2x text-muted"></i>
                    </div>
                @endif
            </div>
            <div class="book-info">
                <h3>{{ Str::limit($book->judul, 30) }}</h3>
                <p>{{ $book->penulis ?? 'Penulis Misterius' }}</p>
                <div class="stock-info">
                    <span class="stock-badge">Tersedia: {{ $book->stok }}</span>
                    <i class="fas fa-arrow-right" style="color: var(--neon-blue)"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center pb-5">
        {{ $books->links() }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function prosesCari() {
        let keyword = $('#inputCari').val();
        let kategori = $('#filterKat').val();
        let grid = $('#gridBuku');

        grid.css('opacity', '0.3');

        $.ajax({
            url: '{{ route("katalog.filter") }}',
            method: 'GET',
            data: { search: keyword, kategori: kategori },
            success: function(res) {
                let html = '';
                if(res.books.length > 0) {
                    res.books.forEach((b, i) => {
                        let img = b.gambar ? `/storage/${b.gambar}` : 'https://via.placeholder.com/220x280';
                        let kat = b.kategori ? b.kategori.nama : 'Umum';
                        html += `
                        <div class="book-card" style="animation-delay: ${i * 0.05}s">
                            <div class="img-wrapper">
                                <span class="category-tag">${kat}</span>
                                <img src="${img}">
                            </div>
                            <div class="book-info">
                                <h3>${b.judul}</h3>
                                <p>${b.penulis || 'Penulis Misterius'}</p>
                                <div class="stock-info">
                                    <span class="stock-badge">Tersedia: ${b.stok}</span>
                                    <i class="fas fa-arrow-right" style="color: var(--neon-blue)"></i>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="text-center w-100 py-5"><h3>Yah, bukunya tidak ketemu...</h3></div>';
                }
                grid.html(html).css('opacity', '1');
            }
        });
    }

    // Enter Key Search
    $('#inputCari').on('keypress', function(e) { if(e.which === 13) prosesCari(); });
</script>
@endsection