@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f7fb;
        font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    /* Header */
    .catalog-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 50px 0;
        text-align: center;
        color: white;
        margin-bottom: 40px;
    }

    .catalog-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .catalog-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    /* Container */
    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ========== REKOMENDASI BUKU POPULER ========== */
    .rekomendasi-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .rekomendasi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .rekomendasi-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rekomendasi-title i {
        font-size: 28px;
        color: #ff6d00;
    }

    .rekomendasi-title h2 {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .rekomendasi-badge {
        background: #fff3e0;
        color: #ff6d00;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
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
        padding: 15px;
        background: #f8f9fa;
        border-radius: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .rekomendasi-item:hover {
        background: #f0f2f5;
        transform: translateX(5px);
    }

    .rank-number {
        width: 45px;
        height: 45px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
        flex-shrink: 0;
    }

    .rank-1 {
        background: #ffd700;
        color: #333;
    }

    .rank-2 {
        background: #c0c0c0;
        color: #333;
    }

    .rank-3 {
        background: #cd7f32;
        color: white;
    }

    .rekomendasi-info {
        flex: 1;
    }

    .rekomendasi-judul {
        font-weight: 700;
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
    }

    .rekomendasi-meta {
        font-size: 12px;
        color: #666;
    }

    .rekomendasi-meta i {
        margin-right: 5px;
    }

    .rekomendasi-stats {
        font-size: 14px;
        font-weight: 600;
        color: #667eea;
        flex-shrink: 0;
    }

    /* ========== SEARCH & FILTER ========== */
    .search-filter-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .search-box {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .search-box input {
        flex: 1;
        padding: 14px 20px;
        border: 1px solid #e0e0e0;
        border-radius: 50px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-box button {
        background: #667eea;
        border: none;
        border-radius: 50px;
        padding: 0 30px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-box button:hover {
        background: #5a67d8;
    }

    .filter-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }

    .total-buku {
        font-size: 14px;
        color: #666;
    }

    .total-buku i {
        margin-right: 8px;
        color: #667eea;
    }

    .kategori-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .kategori-label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .filter-chip {
        padding: 6px 18px;
        border-radius: 30px;
        background: #f0f2f5;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: #666;
    }

    .filter-chip:hover {
        background: #667eea;
        color: white;
    }

    .filter-chip.active {
        background: #667eea;
        color: white;
    }

    /* ========== KATALOG BUKU ========== */
    .katalog-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .katalog-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .katalog-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .book-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        border: 1px solid #f0f0f0;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .book-cover {
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        font-size: 55px;
        color: rgba(255, 255, 255, 0.8);
    }

    .stock-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.7);
        color: white;
    }

    .stock-badge.available {
        background: #10b981;
    }

    .stock-badge.low {
        background: #f59e0b;
    }

    .stock-badge.out {
        background: #ef4444;
    }

    .book-info {
        padding: 15px;
    }

    .book-judul {
        font-size: 15px;
        font-weight: 700;
        color: #333;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .book-penulis {
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }

    .book-penulis i {
        margin-right: 5px;
        font-size: 11px;
    }

    .book-kategori {
        display: inline-block;
        font-size: 11px;
        background: #f0f2f5;
        padding: 4px 12px;
        border-radius: 15px;
        color: #667eea;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px;
        background: #f8f9fa;
        border-radius: 15px;
    }

    .empty-state i {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        font-size: 18px;
        color: #666;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
        color: #999;
    }

    /* Loading */
    .loading {
        text-align: center;
        padding: 60px;
    }

    .loading i {
        font-size: 40px;
        color: #667eea;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pagination .page-link {
        padding: 8px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        color: #667eea;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination .page-link:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .active .page-link {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-header h1 { font-size: 24px; }
        .rekomendasi-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .rekomendasi-item { flex-wrap: wrap; }
        .rank-number { width: 35px; height: 35px; font-size: 16px; }
        .books-grid { grid-template-columns: 1fr; }
        .search-box { flex-direction: column; }
        .filter-info { flex-direction: column; align-items: flex-start; }
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
                    <i class="fas fa-chart-line"></i> {{ $book->total_dipinjam ?? 0 }}x dipinjam
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ==================== SEARCH & FILTER ==================== -->
    <div class="search-filter-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari judul atau penulis...">
            <button id="searchBtn"><i class="fas fa-search me-1"></i> Cari</button>
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
            <h3><i class="fas fa-layer-group me-2"></i>Koleksi Buku</h3>
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
        // Search button click
        $('#searchBtn').on('click', function() {
            currentSearch = $('#searchInput').val();
            loadBooks();
        });

        // Search on enter key
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                currentSearch = $(this).val();
                loadBooks();
            }
        });

        // Filter category click
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
                    ${(currentSearch || currentCategory) ? '<button class="btn btn-primary mt-3" onclick="resetFilters()">Reset Filter</button>' : ''}
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