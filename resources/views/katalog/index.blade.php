@extends('layouts.app')

@section('content')
<style>
    /* ============================================================
       1. TOTAL RESET & FUTURISTIC OVERRIDE
       ============================================================ */
    /* Menghilangkan elemen default AdminLTE / Bootstrap yang mengganggu */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: #050510 !important; 
        min-height: 100vh;
    }

    :root {
        --neon-pink: #ff00ff;
        --neon-purple: #9d00ff;
        --neon-blue: #00d4ff;
        --glass-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    body {
        background: radial-gradient(circle at top right, #1a0b2e, #050510);
        color: #fff;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        overflow-x: hidden;
    }

    /* Background Neon Glows */
    .glow-1 { position: fixed; top: -150px; right: -150px; width: 500px; height: 500px; background: var(--neon-purple); filter: blur(180px); opacity: 0.2; pointer-events: none; }
    .glow-2 { position: fixed; bottom: -150px; left: -150px; width: 500px; height: 500px; background: var(--neon-pink); filter: blur(180px); opacity: 0.15; pointer-events: none; }

    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
        position: relative;
        z-index: 1;
    }

    /* ============================================================
       2. HEADER SECTION
       =========================================================== */
    .hero-section {
        padding: 80px 0 50px;
        text-align: center;
    }

    .hero-section img {
        width: 60px;
        filter: drop-shadow(0 0 10px var(--neon-pink));
        margin-bottom: 20px;
    }

    .futuristic-title {
        font-size: 4rem;
        font-weight: 900;
        letter-spacing: 4px;
        background: linear-gradient(135deg, var(--neon-pink), var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-transform: uppercase;
        margin-bottom: 10px;
        filter: drop-shadow(0 0 15px rgba(255, 0, 255, 0.4));
    }

    .hero-section p {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        letter-spacing: 2px;
    }

    /* ============================================================
       3. SEARCH ENGINE GLASSMORPHISM
       =========================================================== */
    .search-engine {
        max-width: 1000px;
        margin: 40px auto 60px;
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: 100px;
        padding: 10px 10px 10px 35px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.05);
    }

    .search-engine input {
        background: transparent;
        border: none;
        color: #fff;
        flex: 2;
        font-size: 16px;
        outline: none;
    }

    .search-engine select {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.7);
        flex: 1;
        outline: none;
        border-left: 1px solid var(--glass-border);
        padding-left: 20px;
        cursor: pointer;
    }

    .btn-explore {
        background: linear-gradient(45deg, var(--neon-pink), var(--neon-purple));
        color: white;
        border: none;
        padding: 15px 45px;
        border-radius: 100px;
        font-weight: 800;
        letter-spacing: 1px;
        transition: 0.3s;
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.4);
    }

    .btn-explore:hover {
        transform: scale(1.05);
        box-shadow: 0 0 30px var(--neon-pink);
    }

    /* ============================================================
       4. TOP 10 TRENDING (HORIZONTAL)
       =========================================================== */
    .trending-container {
        margin-bottom: 80px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .section-label h3 { font-weight: 800; color: #fff; margin: 0; letter-spacing: 1px; }
    .line { flex: 1; height: 1px; background: linear-gradient(to right, var(--neon-purple), transparent); }

    .horizontal-scroll {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        padding: 20px 0;
        scroll-behavior: smooth;
    }

    .horizontal-scroll::-webkit-scrollbar { height: 4px; }
    .horizontal-scroll::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 10px; }

    .trending-card {
        min-width: 200px;
        position: relative;
        transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .trending-card:hover { transform: scale(1.15) translateY(-10px); z-index: 10; }

    .trending-card img {
        width: 200px;
        height: 300px;
        object-fit: cover;
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 30px rgba(0,0,0,0.5);
    }

    .rank-tag {
        position: absolute;
        top: -15px; left: -15px;
        background: var(--neon-pink);
        color: white;
        width: 50px; height: 50px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 1.4rem;
        box-shadow: 0 0 15px var(--neon-pink);
        transform: rotate(-10deg);
    }

    /* ============================================================
       5. MAIN CATALOG GRID
       =========================================================== */
    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 40px;
        padding-bottom: 100px;
    }

    .book-node {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 25px;
        padding: 15px;
        transition: 0.3s;
        position: relative;
        overflow: hidden;
    }

    .book-node:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--neon-blue);
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.15);
    }

    .book-node img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 18px;
        margin-bottom: 15px;
    }

    .book-node h5 { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 5px; }
    .book-node p { font-size: 13px; color: rgba(255,255,255,0.5); margin-bottom: 15px; }

    .node-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-pill {
        font-size: 10px;
        padding: 5px 12px;
        border-radius: 50px;
        background: rgba(0, 212, 255, 0.1);
        color: var(--neon-blue);
        border: 1px solid var(--neon-blue);
        text-transform: uppercase;
        font-weight: 700;
    }

    .stock-counter { font-size: 11px; font-weight: 700; color: var(--neon-pink); }

    /* Floating Back Button */
    .btn-exit {
        position: fixed;
        bottom: 40px; left: 40px;
        padding: 15px 35px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 100px;
        color: white;
        text-decoration: none !important;
        font-weight: 800;
        z-index: 100;
        transition: 0.3s;
        display: flex; align-items: center; gap: 10px;
    }

    .btn-exit:hover { background: #fff; color: #000; }

</style>

<div class="glow-1"></div>
<div class="glow-2"></div>

<a href="{{ url('/') }}" class="btn-exit">
    <i class="fas fa-arrow-left"></i> EXIT TO DASHBOARD
</a>

<div class="hero-section">
    <div class="container-custom">
        <i class="fas fa-book-open fa-3x" style="color: var(--neon-pink); margin-bottom: 20px;"></i>
        <h1 class="futuristic-title">Virtual Catalog</h1>
        <p>SDN BERAT WETAN 1 - DIGITAL LIBRARY SYSTEM v2.0</p>
    </div>
</div>

<div class="container-custom">
    <div class="search-engine">
        <i class="fas fa-search text-muted"></i>
        <input type="text" id="mainSearch" placeholder="Scan database for titles, authors, or ISBN...">
        <select id="catSelect">
            <option value="">ALL CATEGORIES</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
            @endforeach
        </select>
        <button class="btn-explore" onclick="triggerScan()">SCAN DATABASE</button>
    </div>

    @if($popularBooks->count() > 0)
    <div class="trending-container">
        <div class="section-label">
            <h3><i class="fas fa-bolt text-warning me-2"></i> TRENDING DATA</h3>
            <div class="line"></div>
        </div>
        <div class="horizontal-scroll">
            @foreach($popularBooks as $index => $pb)
            <div class="trending-card">
                <div class="rank-tag">{{ $index + 1 }}</div>
                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="">
                @else
                    <div style="width:200px; height:300px; background:#222; border-radius:20px; display:flex; align-items:center; justify-content:center">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="section-label">
        <h3><i class="fas fa-layer-group text-info me-2"></i> ALL ARCHIVES</h3>
        <div class="line"></div>
    </div>

    <div id="catalogContainer" class="catalog-grid">
        @foreach($books as $book)
        <div class="book-node">
            @if($book->gambar)
                <img src="{{ asset('storage/'.$book->gambar) }}" alt="">
            @else
                <div style="height:280px; background:#222; border-radius:18px; display:flex; align-items:center; justify-content:center; margin-bottom:15px">
                    <i class="fas fa-image fa-3x text-muted"></i>
                </div>
            @endif
            <h5>{{ Str::limit($book->judul, 35) }}</h5>
            <p>{{ $book->penulis ?? 'Unknown Author' }}</p>
            <div class="node-footer">
                <span class="category-pill">{{ $book->kategori->nama ?? 'General' }}</span>
                <span class="stock-counter">QTY: {{ $book->stok }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center pb-5">
        {{ $books->links() }}
    </div>
</div>

<script>
    function triggerScan() {
        let search = document.getElementById('mainSearch').value;
        let kategori = document.getElementById('catSelect').value;
        let container = document.getElementById('catalogContainer');

        container.style.opacity = '0.3';

        fetch(`{{ route('katalog.filter') }}?search=${search}&kategori=${kategori}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                if(data.books.length > 0) {
                    data.books.forEach(b => {
                        let img = b.gambar ? `/storage/${b.gambar}` : 'https://via.placeholder.com/210x280?text=No+Image';
                        let katName = b.kategori ? b.kategori.nama : 'General';
                        html += `
                        <div class="book-node">
                            <img src="${img}" alt="">
                            <h5>${b.judul}</h5>
                            <p>${b.penulis || 'Unknown Author'}</p>
                            <div class="node-footer">
                                <span class="category-pill">${katName}</span>
                                <span class="stock-counter">QTY: ${b.stok}</span>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="text-center w-100 py-5"><h4>DATA NOT FOUND IN ARCHIVE</h4></div>';
                }
                container.innerHTML = html;
                container.style.opacity = '1';
            });
    }

    // Listener Enter Key
    document.getElementById('mainSearch').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { triggerScan(); }
    });
</script>
@endsection