@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<style>
    /* 1. MENGHILANGKAN SIDEBAR & RESET LAYOUT */
    /* Ganti selector di bawah sesuai dengan class/id sidebar di layouts.app kamu */
    .sidebar, #sidebar-wrapper, .nav-sidebar { display: none !important; }
    .main-content, #page-content-wrapper, .content-wrapper { 
        margin-left: 0 !important; 
        width: 100% !important; 
        padding: 0 !important;
        background: #0f0c29 !important;
    }

    body {
        background: #0f0c29;
        font-family: 'Inter', sans-serif;
        color: #fff;
    }

    .container-custom {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 40px;
    }

    /* ========== HEADER SECTION ========== */
    .catalog-header {
        text-align: center;
        padding: 50px 0 30px;
    }

    .catalog-header h1 {
        font-size: 40px;
        font-weight: 800;
        background: linear-gradient(135deg, #d161ff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
    }

    /* ========== TOP 10 SECTION (HORIZONTAL) ========== */
    .top-section {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 40px;
    }

    .top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .top-title h2 {
        color: #ff9d76;
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    .btn-real-data {
        background: rgba(255, 165, 0, 0.1);
        color: #ffcc00;
        border: 1px solid rgba(255, 204, 0, 0.5);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
    }

    .horizontal-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 15px;
        scroll-behavior: smooth;
    }

    .horizontal-scroll::-webkit-scrollbar { height: 6px; }
    .horizontal-scroll::-webkit-scrollbar-thumb { background: #d161ff; border-radius: 10px; }

    .top-card {
        min-width: 180px;
        position: relative;
    }

    .rank-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ffcc00;
        color: #000;
        font-weight: 900;
        padding: 2px 10px;
        border-radius: 6px;
        z-index: 5;
    }

    .top-card img {
        width: 180px;
        height: 260px;
        object-fit: cover;
        border-radius: 12px;
        transition: transform 0.3s;
    }

    .top-card:hover img { transform: scale(1.05); }

    /* ========== SEARCH BOX ========== */
    .search-container {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50px;
        padding: 8px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 40px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .search-container input, .search-container select {
        background: transparent;
        border: none;
        color: #fff;
        padding: 10px;
        outline: none;
    }

    .search-container input { flex: 2; border-right: 1px solid rgba(255,255,255,0.1); }
    .search-container select { flex: 1; color: rgba(255,255,255,0.7); }

    .btn-cari {
        background: #d161ff;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
    }

    /* ========== GRID KATALOG ========== */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 25px;
        padding-bottom: 50px;
    }

    .book-card {
        background: transparent;
        transition: 0.3s;
    }

    .book-card:hover { transform: translateY(-10px); }

    .poster-wrapper {
        position: relative;
        margin-bottom: 10px;
    }

    .poster-wrapper img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 10px;
    }

    .stock-tag {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(255, 165, 0, 0.9);
        color: white;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 11px;
    }

    .book-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #fff;
    }

    .book-author {
        font-size: 13px;
        color: rgba(255,255,255,0.5);
    }

    /* Back Button */
    .btn-back-home {
        position: fixed;
        bottom: 30px;
        left: 30px;
        background: #a855f7;
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        z-index: 1000;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }
</style>

<a href="{{ url('/') }}" class="btn-back-home">
    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
</a>

<div class="catalog-header">
    <div class="container-custom">
        <div class="d-flex justify-content-center align-items-center gap-2">
             <img src="https://img.icons8.com/color/48/open-book.png" alt="book-icon"/>
             <h1>Katalog Buku</h1>
        </div>
        <p>Koleksi Buku Perpustakaan SDN Berat Wetan 1</p>
    </div>
</div>

<div class="container-custom">
    
    @if(isset($popularBooks) && $popularBooks->count() > 0)
    <div class="top-section">
        <div class="top-header">
            <div class="top-title">
                <i class="fas fa-trophy text-warning"></i>
                <h2>Top {{ $popularBooks->count() }} Paling Populer</h2>
                <p class="text-muted m-0 small">Buku yang paling sering dipinjam murid</p>
            </div>
            <div class="btn-real-data">
                <i class="fas fa-chart-line"></i> Berdasarkan data peminjaman real
            </div>
        </div>

        <div class="horizontal-scroll">
            @foreach($popularBooks as $index => $book)
            <div class="top-card">
                <div class="rank-badge">#{{ $index + 1 }}</div>
                @if($book->gambar)
                    <img src="{{ asset('storage/' . $book->gambar) }}" alt="{{ $book->judul }}">
                @else
                    <div style="width:180px;height:260px;background:#333;border-radius:12px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-book fa-3x"></i>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari judul atau penulis...">
        <select id="categoryFilter">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
            @endforeach
        </select>
        <button class="btn-cari" id="btnCari">
            <i class="fas fa-search me-1"></i> Cari
        </button>
    </div>

    <div id="booksSection">
        <div class="books-grid">
            @foreach($books as $book)
            <div class="book-card">
                <div class="poster-wrapper">
                    @if($book->gambar)
                        <img src="{{ asset('storage/' . $book->gambar) }}" alt="{{ $book->judul }}">
                    @else
                        <div style="height:250px;background:#333;border-radius:10px;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-image fa-2x"></i>
                        </div>
                    @endif
                    <div class="stock-tag">Sisa {{ $book->stok }}</div>
                </div>
                <div class="book-title">{{ Str::limit($book->judul, 35) }}</div>
                <div class="book-author">{{ $book->penulis ?? 'Penulis tidak diketahui' }}</div>
                <div class="mt-2">
                    <span style="font-size: 11px; background: rgba(255,255,255,0.1); padding: 3px 10px; border-radius: 20px;">
                        {{ $book->kategori->nama ?? 'Umum' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-center pb-5">
        {{ $books->links() }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btnCari').on('click', function() {
            let search = $('#searchInput').val();
            let kategori = $('#categoryFilter').val();
            loadBooks(search, kategori);
        });

        function loadBooks(search, kategori) {
            $('#booksSection').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            
            $.ajax({
                url: '{{ route("katalog.filter") }}',
                method: 'GET',
                data: { search: search, kategori: kategori },
                success: function(response) {
                    if (response.success) {
                        renderBooks(response.books);
                    }
                }
            });
        }

        function renderBooks(books) {
            if(books.length == 0) {
                $('#booksSection').html('<div class="text-center py-5">Buku tidak ditemukan.</div>');
                return;
            }

            let html = '<div class="books-grid">';
            books.forEach(book => {
                let img = book.gambar ? `/storage/${book.gambar}` : 'https://via.placeholder.com/180x250';
                html += `
                    <div class="book-card">
                        <div class="poster-wrapper">
                            <img src="${img}" alt="${book.judul}">
                            <div class="stock-tag">Sisa ${book.stok}</div>
                        </div>
                        <div class="book-title">${book.judul}</div>
                        <div class="book-author">${book.penulis ?? ''}</div>
                    </div>
                `;
            });
            html += '</div>';
            $('#booksSection').html(html);
        }
    });
</script>
@endsection