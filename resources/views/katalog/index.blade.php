@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #0f0c29 0%, #1a1a3e 50%, #24243e 100%);
        min-height: 100vh;
        color: #fff;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #7c9bff, #b38dff, #ff9bc4);
        border-radius: 10px;
    }

    /* Header Section */
    .catalog-header {
        position: relative;
        padding: 60px 0 80px;
        text-align: center;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(124, 155, 255, 0.15), rgba(179, 141, 255, 0.15), rgba(255, 155, 196, 0.15));
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .catalog-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(124, 155, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .catalog-header h1 {
        font-size: 48px;
        font-weight: 800;
        background: linear-gradient(135deg, #7c9bff, #b38dff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .catalog-header p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
        position: relative;
        z-index: 1;
    }

    /* Container */
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }

    /* ========== REKOMENDASI SECTION ========== */
    .rekomendasi-section {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 30px;
        margin: -40px 0 40px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 2;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .rekomendasi-header {
        display: flex;
    align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .rekomendasi-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rekomendasi-title i {
        font-size: 32px;
        background: linear-gradient(135deg, #ffd700, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .rekomendasi-title h2 {
        font-size: 24px;
        font-weight: 700;
        background: linear-gradient(135deg, #fff, rgba(255,255,255,0.8));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .rekomendasi-badge {
        background: linear-gradient(135deg, rgba(124, 155, 255, 0.2), rgba(179, 141, 255, 0.2));
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 13px;
        color: #a8c0ff;
        border: 1px solid rgba(124, 155, 255, 0.3);
    }

    /* List Rekomendasi */
    .rekomendasi-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .rekomendasi-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 18px 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .rekomendasi-item:hover {
        background: rgba(124, 155, 255, 0.1);
        transform: translateX(8px);
        border-color: rgba(124, 155, 255, 0.3);
    }

    .rank-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #7c9bff, #b38dff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 5px 15px rgba(124, 155, 255, 0.3);
    }

    .rank-1 {
        background: linear-gradient(135deg, #ffd700, #ff9bc4);
        box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4);
    }

    .rank-2 {
        background: linear-gradient(135deg, #c0c0c0, #a8c0ff);
    }

    .rank-3 {
        background: linear-gradient(135deg, #cd7f32, #ff9bc4);
    }

    .rekomendasi-info {
        flex: 1;
    }

    .rekomendasi-judul {
        font-weight: 700;
        font-size: 18px;
        color: #fff;
        margin-bottom: 8px;
    }

    .rekomendasi-meta {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    .rekomendasi-meta i {
        margin-right: 6px;
        color: #7c9bff;
    }

    .rekomendasi-stats {
        font-size: 14px;
        font-weight: 600;
        background: linear-gradient(135deg, #7c9bff, #ff9bc4);
        padding: 8px 16px;
        border-radius: 30px;
        flex-shrink: 0;
    }

    /* ========== SEARCH & FILTER ========== */
    .search-filter-section {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .search-box {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .search-box input {
        flex: 1;
        padding: 16px 24px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 60px;
        font-size: 14px;
        color: #fff;
        transition: all 0.3s;
    }

    .search-box input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .search-box input:focus {
        outline: none;
        border-color: #7c9bff;
        background: rgba(124, 155, 255, 0.1);
        box-shadow: 0 0 20px rgba(124, 155, 255, 0.2);
    }

    .search-box button {
        background: linear-gradient(135deg, #7c9bff, #b38dff);
        border: none;
        border-radius: 60px;
        padding: 0 35px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-box button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(124, 155, 255, 0.4);
    }

    .filter-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .total-buku {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 30px;
    }

    .total-buku i {
        margin-right: 8px;
        color: #7c9bff;
    }

    .kategori-filter {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kategori-label {
        font-size: 14px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8);
    }

    .filter-chip {
        padding: 8px 22px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.7);
    }

    .filter-chip:hover {
        background: rgba(124, 155, 255, 0.2);
        border-color: #7c9bff;
        transform: translateY(-2px);
    }

    .filter-chip.active {
        background: linear-gradient(135deg, #7c9bff, #b38dff);
        color: white;
        border-color: transparent;
    }

    /* ========== KATALOG BUKU ========== */
    .katalog-section {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(5px);
        border-radius: 24px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .katalog-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .katalog-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .katalog-header h3 i {
        background: linear-gradient(135deg, #7c9bff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-right: 10px;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .book-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
    }

    .book-card:hover {
        transform: translateY(-8px);
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(124, 155, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .book-cover {
        height: 200px;
        background: linear-gradient(135deg, rgba(124, 155, 255, 0.2), rgba(179, 141, 255, 0.2));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-cover i {
        font-size: 60px;
        background: linear-gradient(135deg, #7c9bff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .stock-badge.available {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }

    .stock-badge.low {
        background: rgba(245, 158, 11, 0.9);
        color: white;
    }

    .stock-badge.out {
        background: rgba(239, 68, 68, 0.9);
        color: white;
    }

    .book-info {
        padding: 18px;
    }

    .book-judul {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .book-penulis {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 12px;
    }

    .book-penulis i {
        margin-right: 6px;
        color: #7c9bff;
    }

    .book-kategori {
        display: inline-block;
        font-size: 11px;
        background: linear-gradient(135deg, rgba(124, 155, 255, 0.2), rgba(179, 141, 255, 0.2));
        padding: 5px 14px;
        border-radius: 20px;
        color: #a8c0ff;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
    }

    .empty-state i {
        font-size: 80px;
        background: linear-gradient(135deg, #7c9bff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 20px;
        color: #fff;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.5);
    }

    /* Loading */
    .loading {
        text-align: center;
        padding: 80px;
    }

    .loading i {
        font-size: 50px;
        background: linear-gradient(135deg, #7c9bff, #ff9bc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pagination .page-link {
        padding: 10px 18px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #7c9bff, #b38dff);
        color: white;
        transform: translateY(-2px);
    }

    .pagination .active .page-link {
        background: linear-gradient(135deg, #7c9bff, #b38dff);
        color: white;
        border-color: transparent;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-custom { padding: 0 20px; }
        .catalog-header h1 { font-size: 32px; }
        .rekomendasi-header { flex-direction: column; align-items: flex-start; }
        .rekomendasi-item { flex-wrap: wrap; }
        .rank-number { width: 40px; height: 40px; font-size: 18px; }
        .rekomendasi-judul { font-size: 15px; }
        .books-grid { grid-template-columns: 1fr; }
        .search-box { flex-direction: column; }
        .filter-info { flex-direction: column; align-items: flex-start; }
        .kategori-filter { width: 100%; overflow-x: auto; }
    }

    @media (max-width: 480px) {
        .rekomendasi-stats { font-size: 11px; padding: 5px 12px; }
        .rekomendasi-meta { font-size: 11px; }
    }
</style>

<div class="catalog-header">
    <h1><i class="fas fa-book-open me-2"></i>Katalog Buku</h1>
    <p>Koleksi Buku Perpustakaan SDN Berat Wetan 1</p>
</div>

<div class="container-custom">
    <!-- ==================== REKOMENDASI BUKU POPULER ==================== -->
    @if(isset($popularBooks) && $popularBooks->count() > 0)
    <div class="rekomendasi-section">
        <div class="rekomendasi-header">
            <div class="rekomendasi-title">
                <i class="fas fa-trophy"></i>
                <h2>Top {{ $popularBooks->count() }} Paling Populer</h2>
            </div>
            <span class="rekomendasi-badge">
                <i class="fas fa-chart-line me-1"></i>Buku yang paling sering dipinjam
            </span>
        </div>
        <div class="rekomendasi-list">
            @foreach($popularBooks as $index => $book)
            <div class="rekomendasi-item">
                <div class="rank-number rank-{{ $index+1 }} @if($index+1 == 1) rank-1 @elseif($index+1 == 2) rank-2 @elseif($index+1 == 3) rank-3 @endif">
                    {{ $index+1 }}
                </div>
                <div class="rekomendasi-info">
                    <div class="rekomendasi-judul">{{ $book->judul }}</div>
                    <div class="rekomendasi-meta">
                        <i class="fas fa-user"></i> {{ $book->penulis ?: 'Penulis tidak diketahui' }}
                        @if($book->penerbit)
                        | <i class="fas fa-building"></i> {{ $book->penerbit }}
                        @endif
                    </div>
                </div>
                <div class="rekomendasi-stats">
                    <i class="fas fa-chart-line me-1"></i> {{ $book->total_dipinjam ?? 0 }}x dipinjam
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ==================== SEARCH & FILTER ==================== -->
    <div class="search-filter-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Cari judul, penulis, atau penerbit...">
            <button id="searchBtn"><i class="fas fa-search me-2"></i>Cari</button>
        </div>
        <div class="filter-info">
            <div class="total-buku">
                <i class="fas fa-book"></i> Total <span id="bookCount">{{ $books->total() }}</span> buku
            </div>
            <div class="kategori-filter">
                <span class="kategori-label"><i class="fas fa-tags me-1"></i>Kategori:</span>
                <button class="filter-chip active" data-category="">Semua Kategori</button>
                @foreach($kategoris as $kategori)
                    <button class="filter-chip" data-category="{{ $kategori->id }}">{{ $kategori->nama }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ==================== KATALOG BUKU ==================== -->
    <div class="katalog-section">
        <div class="katalog-header">
            <h3><i class="fas fa-layer-group"></i> Koleksi Buku</h3>
        </div>
        
        <div id="booksSection">
            <div class="books-grid">
                @foreach($books as $book)
                <div class="book-card">
                    <div class="book-cover">
                        @if($book->gambar)
                            <img src="{{ asset('storage/' . $book->gambar) }}" alt="{{ $book->judul }}">
                        @else
                            <i class="fas fa-book"></i>
                        @endif
                        <span class="stock-badge 
                            @if($book->stok > 5) available 
                            @elseif($book->stok > 0) low 
                            @else out @endif">
                            @if($book->stok > 0)
                                Sisa {{ $book->stok }}
                            @else
                                Habis
                            @endif
                        </span>
                    </div>
                    <div class="book-info">
                        <div class="book-judul">{{ Str::limit($book->judul, 45) }}</div>
                        <div class="book-penulis">
                            <i class="fas fa-user"></i> {{ Str::limit($book->penulis, 25) ?: 'Penulis tidak diketahui' }}
                        </div>
                        <div class="book-kategori">
                            <i class="fas fa-tag me-1"></i> {{ $book->kategori->nama ?? 'Tanpa Kategori' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($books->count() == 0)
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h4>Belum Ada Buku</h4>
                <p>Belum ada buku yang tersedia di katalog</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($books->count() > 0)
        <div class="pagination-wrapper">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentCategory = '';
    let currentSearch = '';

    $(document).ready(function() {
        $('#searchBtn').on('click', function() {
            currentSearch = $('#searchInput').val();
            loadBooks();
        });

        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                currentSearch = $(this).val();
                loadBooks();
            }
        });

        $('.filter-chip').on('click', function() {
            $('.filter-chip').removeClass('active');
            $(this).addClass('active');
            currentCategory = $(this).data('category');
            loadBooks();
        });
    });

    function loadBooks() {
        $('#booksSection').html('<div class="loading"><i class="fas fa-spinner fa-spin"></i><p>Memuat buku...</p></div>');
        
        $.ajax({
            url: '{{ route("katalog.filter") }}',
            method: 'GET',
            data: {
                kategori: currentCategory,
                search: currentSearch
            },
            success: function(response) {
                if (response.success) {
                    displayBooks(response.books);
                    $('#bookCount').text(response.books.length);
                } else {
                    $('#booksSection').html(`
                        <div class="empty-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <h4>Error</h4>
                            <p>${response.message || 'Gagal memuat buku'}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#booksSection').html(`
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Error</h4>
                        <p>Gagal memuat data. Silahkan refresh halaman.</p>
                    </div>
                `);
            }
        });
    }

    function displayBooks(books) {
        if (!books || books.length === 0) {
            let msg = currentSearch ? `Tidak ditemukan "${currentSearch}"` : 'Tidak ada buku yang tersedia';
            $('#booksSection').html(`
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h4>Tidak Ada Buku</h4>
                    <p>${msg}</p>
                    ${(currentSearch || currentCategory) ? '<button class="btn-reset" onclick="resetFilters()" style="margin-top:20px;padding:10px 25px;background:linear-gradient(135deg,#7c9bff,#b38dff);border:none;border-radius:30px;color:white;cursor:pointer;">Reset Filter</button>' : ''}
                </div>
            `);
            return;
        }
        
        let html = `<div class="books-grid">`;
        
        books.forEach(book => {
            let stockClass = book.stok > 5 ? 'available' : (book.stok > 0 ? 'low' : 'out');
            let stockText = book.stok > 0 ? `Sisa ${book.stok}` : 'Habis';
            
            html += `
                <div class="book-card">
                    <div class="book-cover">
                        ${book.gambar ? `<img src="/storage/${book.gambar}" alt="${escapeHtml(book.judul)}">` : 
                                        `<i class="fas fa-book"></i>`}
                        <span class="stock-badge ${stockClass}">
                            ${stockText}
                        </span>
                    </div>
                    <div class="book-info">
                        <div class="book-judul">${escapeHtml(book.judul)}</div>
                        <div class="book-penulis">
                            <i class="fas fa-user"></i> ${escapeHtml(book.penulis) || 'Penulis tidak diketahui'}
                        </div>
                        <div class="book-kategori">
                            <i class="fas fa-tag me-1"></i> ${escapeHtml(book.kategori?.nama || 'Tanpa Kategori')}
                        </div>
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        
        $('#booksSection').html(html);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    window.resetFilters = function() {
        currentCategory = '';
        currentSearch = '';
        $('#searchInput').val('');
        $('.filter-chip').removeClass('active');
        $('.filter-chip[data-category=""]').addClass('active');
        loadBooks();
    };
</script>
@endsection