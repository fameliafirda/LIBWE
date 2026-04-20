@extends('layouts.app')

@section('content')
<style>
    /* ============================================================
       1. TOTAL RESET & STICKY HEADER SETUP
       ============================================================ */
    /* Hapus elemen default AdminLTE / Bootstrap */
    .main-header, .main-sidebar, .content-header, .main-footer, hr, .breadcrumb { 
        display: none !important; 
    }
    
    .content-wrapper { 
        margin-left: 0 !important; 
        padding: 0 !important; 
        background: #050510 !important; 
        min-height: 100vh;
        overflow: hidden; /* Untuk partikel background */
    }

    :root {
        --neon-pink: #ff00ff;
        --neon-purple: #9d00ff;
        --neon-blue: #00d4ff;
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    body {
        background: radial-gradient(circle at top right, #1a0b2e, #050510);
        color: #fff;
        font-family: 'Inter', sans-serif;
        overflow-x: hidden;
    }

    .container-custom {
        max-width: 1350px;
        margin: 0 auto;
        padding: 0 40px;
        position: relative;
        z-index: 10;
    }

    /* ============================================================
       2. NEON PARTICLES BACKGROUND ANIMATION
       ============================================================ */
    .particles-container {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        border-radius: 50%;
        filter: blur(5px);
        opacity: 0.1;
        animation: particleFly 15s linear infinite;
    }

    @keyframes particleFly {
        0% { transform: translateY(100vh) scale(0); }
        100% { transform: translateY(-10vh) scale(1); }
    }

    /* ============================================================
       3. STICKY TOP BAR & LOGO
       =========================================================== */
    .top-bar-sticky {
        position: sticky;
        top: 0;
        left: 0;
        width: 100%;
        background: rgba(5, 5, 16, 0.85);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--glass-border);
        padding: 20px 0;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .top-bar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo-future {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-future img {
        width: 45px;
        filter: drop-shadow(0 0 10px var(--neon-pink));
    }

    .title-glow {
        font-size: 2rem;
        font-weight: 900;
        letter-spacing: 1px;
        background: linear-gradient(135deg, var(--neon-pink), var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-transform: uppercase;
        filter: drop-shadow(0 0 10px rgba(255, 0, 255, 0.3));
    }

    .school-desc {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
        letter-spacing: 1px;
    }

    /* ============================================================
       4. CYBER SEARCH & FILTER SECTION
       =========================================================== */
    .search-filter-box {
        display: flex;
        align-items: center;
        gap: 20px;
        max-width: 800px;
    }

    .cyber-search {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 100px;
        padding: 5px 5px 5px 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 3;
        transition: 0.3s;
    }

    .cyber-search:focus-within {
        border-color: var(--neon-pink);
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.2);
    }

    .cyber-search input {
        background: transparent;
        border: none;
        color: #fff;
        flex: 1;
        outline: none;
        font-size: 14px;
    }

    .btn-cyber-cari {
        background: linear-gradient(45deg, var(--neon-pink), var(--neon-purple));
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 100px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-cyber-cari:hover { transform: scale(1.05); box-shadow: 0 0 20px var(--neon-pink); }

    .cyber-filter {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 100px;
        padding: 10px 20px;
        color: rgba(255,255,255,0.7);
        outline: none;
        cursor: pointer;
        font-size: 13px;
    }

    /* ============================================================
       5. TRENDING SECTIONS (HORIZONTAL)
       =========================================================== */
    .trending-section {
        padding: 50px 0;
    }

    .label-tren {
        font-weight: 800;
        font-size: 1.3rem;
        color: var(--neon-blue);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tren-line { flex: 1; height: 1px; background: linear-gradient(to right, var(--neon-blue), transparent); }

    .trending-scroll {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        padding: 20px 0;
    }

    .trending-scroll::-webkit-scrollbar { height: 4px; }
    .trending-scroll::-webkit-scrollbar-thumb { background: var(--neon-purple); border-radius: 10px; }

    .card-tren {
        min-width: 180px;
        position: relative;
        transition: all 0.5s;
    }

    .card-tren:hover { transform: scale(1.1) translateY(-10px); z-index: 20; }

    .card-tren img {
        width: 180px;
        height: 270px;
        object-fit: cover;
        border-radius: 15px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
    }

    .badge-rank {
        position: absolute;
        top: -10px; left: -10px;
        background: var(--neon-pink);
        color: white;
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 900;
        box-shadow: 0 0 10px var(--neon-pink);
    }

    /* ============================================================
       6. MAIN ARCHIVE GRID
       =========================================================== */
    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 30px;
        padding-bottom: 80px;
    }

    .grid-node {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 15px;
        transition: 0.3s;
        position: relative;
        cursor: pointer;
    }

    /* Efek hover pada kartu buku utama */
    .grid-node:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--neon-purple);
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(157, 0, 255, 0.2);
    }

    .grid-node img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .grid-node h5 { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 5px; }
    .grid-node p { font-size: 12px; color: rgba(255,255,255,0.5); margin-bottom: 12px; }

    .node-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pil-kategori {
        font-size: 9px;
        padding: 3px 10px;
        border-radius: 20px;
        background: rgba(0, 212, 255, 0.1);
        color: var(--neon-blue);
        border: 1px solid var(--neon-blue);
        text-transform: uppercase;
        font-weight: 700;
    }

    .qty-stok { font-size: 10px; font-weight: 700; color: var(--neon-pink); }

</style>

<div class="particles-container" id="particles"></div>

<div class="top-bar-sticky">
    <div class="container-custom">
        <div class="top-bar-content">
            <div class="logo-future">
                <img src="https://img.icons8.com/color/96/open-book.png" alt="book-icon">
                <div>
                    <h1 class="title-glow">Katalog Buku</h1>
                    <p class="school-desc">Perpustakaan Online SDN BERATWETAN 1</p>
                </div>
            </div>

            <div class="search-filter-box">
                <div class="cyber-search">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" id="kunci_cari" placeholder="Masukkan judul buku atau nama penulis...">
                    <button class="btn-cyber-cari" onclick="temukanBuku()">CARI</button>
                </div>
                
                <select id="kategori_pilih" class="cyber-filter">
                    <option value="">SEMUA KATEGORI</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="container-custom mt-5">
    
    @if($popularBooks->count() > 0)
    <div class="trending-section">
        <div class="label-tren">
            <i class="fas fa-bolt text-warning"></i>
            TRENDING DATA PUSTAKAWAN
            <div class="tren-line"></div>
        </div>
        <div class="trending-scroll">
            @foreach($popularBooks as $index => $pb)
            <div class="card-tren">
                <div class="badge-rank">#{{ $index + 1 }}</div>
                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="{{ $pb->judul }}">
                @else
                    <div style="width:180px;height:270px;background:#222;border-radius:15px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="label-tren mt-5">
        <i class="fas fa-layer-group text-info"></i>
        SEMUA ARSIP BUKU
        <div class="tren-line"></div>
    </div>

    <div id="catalogNodeContainer" class="catalog-grid">
        @foreach($books as $book)
        <div class="grid-node">
            @if($book->gambar)
                <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
            @else
                <div style="height:250px; background:#222; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:12px">
                    <i class="fas fa-image fa-2x text-muted"></i>
                </div>
            @endif
            <h5>{{ Str::limit($book->judul, 35) }}</h5>
            <p>{{ $book->penulis ?? 'Penulis tidak diketahui' }}</p>
            <div class="node-meta">
                <span class="pil-kategori">{{ $book->kategori->nama ?? 'Umum' }}</span>
                <span class="qty-stok">STOK: {{ $book->stok }}</span>
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
    // PARTIKEL BACKGROUND ANIMATION SCRIPT
    function createParticles() {
        const container = document.getElementById('particles');
        const colors = ['#ff00ff', '#9d00ff', '#00d4ff'];
        
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Atur ukuran & warna acak
            const size = Math.random() * 5 + 2 + 'px';
            particle.style.width = size;
            particle.style.height = size;
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            
            // Atur posisi & kecepatan acak
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.animationDuration = (Math.random() * 10 + 5) + 's';
            particle.style.animationDelay = (Math.random() * 5) + 's';
            
            container.appendChild(particle);
        }
    }
    createParticles(); // Jalankan partikel

    // AJAX FILTER FUNCTION (UNTUK TOMBOL CARI & DROPDOWN)
    function temukanBuku() {
        let keyword = $('#kunci_cari').val();
        let kategori = $('#kategori_pilih').val();
        let container = $('#catalogNodeContainer');

        container.style.opacity = '0.4'; // Efek loading

        $.ajax({
            url: '{{ route("katalog.filter") }}', // Ganti dengan route filter kamu
            method: 'GET',
            data: { search: keyword, kategori: kategori },
            success: function(response) {
                let html = '<div class="catalog-grid">';
                if(response.books.length > 0) {
                    response.books.forEach(b => {
                        let imgUrl = b.gambar ? `/storage/${b.gambar}` : 'https://via.placeholder.com/190x250?text=No+Image';
                        let katName = b.kategori ? b.kategori.nama : 'Umum';
                        html += `
                        <div class="grid-node">
                            <img src="${imgUrl}" alt="${b.judul}">
                            <h5>${b.judul}</h5>
                            <p>${b.penulis || 'Penulis tidak diketahui'}</p>
                            <div class="node-meta">
                                <span class="pil-kategori">${katName}</span>
                                <span class="qty-stok">STOK: ${b.stok}</span>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="text-center w-100 py-5"><h4>Buku tidak ditemukan.</h4></div>';
                }
                html += '</div>';
                container.innerHTML = html;
                container.style.opacity = '1';
            },
            error: function() {
                container.innerHTML = '<div class="text-center w-100 py-5"><h4>Error mengambil data.</h4></div>';
                container.style.opacity = '1';
            }
        });
    }

    // Listener untuk pencarian otomatis saat dropdown kategori berubah
    $('#kategori_pilih').on('change', function() { temukanBuku(); });

    // Listener Keypress Enter untuk kolom pencarian
    $('#kunci_cari').on('keypress', function(e) { if(e.which === 13) temukanBuku(); });
</script>
@endsection