@extends('layouts.app')

@section('title', 'Katalog Buku Digital')

@section('content')
<style>
    /* ========== FUTURISTIC PASTEL COLOR PALETTE ========== */
    :root {
        --pastel-blue: #7c9bff;
        --pastel-blue-light: #a8c0ff;
        --pastel-blue-bg: #eef3ff;
        --pastel-pink: #ff9bc4;
        --pastel-pink-light: #ffb8d4;
        --pastel-pink-bg: #fff0f5;
        --pastel-purple: #b38dff;
        --pastel-purple-light: #d0b5ff;
        --pastel-purple-bg: #f3edff;
        --pastel-green: #7cff9b;
        --pastel-green-light: #a8ffc0;
        --pastel-orange: #ffb47c;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.3);
        --text-dark: #2d2d5e;
        --text-gray: #6b6b8d;
        --shadow-sm: 0 8px 20px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 12px 28px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.12);
        --radius: 24px;
        --radius-sm: 16px;
        --transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }

    body {
        background: linear-gradient(135deg, var(--pastel-blue-bg) 0%, var(--pastel-purple-bg) 50%, var(--pastel-pink-bg) 100%);
        background-attachment: fixed;
    }

    .page-header {
        background: linear-gradient(135deg, var(--pastel-blue), var(--pastel-purple));
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: floatGlow 8s ease-in-out infinite;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: floatGlow 6s ease-in-out infinite reverse;
    }

    @keyframes floatGlow {
        0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
        50% { transform: translate(30px, -20px) scale(1.1); opacity: 0.8; }
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #fff, rgba(255,255,255,0.8));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Search & Filter Section */
    .search-filter-section {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 32px;
    }

    .search-input {
        width: 100%;
        padding: 12px 20px;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 60px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--pastel-purple);
        box-shadow: 0 0 0 3px rgba(179, 141, 255, 0.2);
    }

    .filter-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
        font-size: 14px;
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .filter-chip {
        padding: 8px 20px;
        border-radius: 60px;
        background: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
    }

    .filter-chip:hover {
        transform: translateY(-2px);
    }

    .filter-chip.active {
        background: linear-gradient(135deg, var(--pastel-purple), var(--pastel-blue));
        color: white;
    }

    /* Books Grid */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
    }

    .book-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
        cursor: pointer;
    }

    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .book-cover {
        height: 180px;
        background: linear-gradient(135deg, var(--pastel-blue-light), var(--pastel-purple-light));
        display: flex;
        align-items: center;
        justify-content: center;
        object-fit: cover;
        width: 100%;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-cover i {
        font-size: 48px;
        color: white;
    }

    .book-info {
        padding: 14px;
    }

    .book-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .book-author {
        font-size: 11px;
        color: var(--text-gray);
        margin-bottom: 8px;
    }

    .book-category {
        font-size: 10px;
        background: linear-gradient(135deg, var(--pastel-pink-light), var(--pastel-blue-light));
        padding: 4px 10px;
        border-radius: 50px;
        color: white;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 60px;
        background: var(--glass-bg);
        border-radius: var(--radius);
    }

    .empty-state i {
        font-size: 64px;
        color: var(--pastel-purple);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .loading {
        text-align: center;
        padding: 40px;
    }

    .loading i {
        font-size: 40px;
        color: var(--pastel-purple);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-reset {
        background: linear-gradient(135deg, var(--pastel-purple), var(--pastel-blue));
        border: none;
        padding: 10px 24px;
        border-radius: 60px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        margin-top: 20px;
    }

    /* Popular Books Section */
    .popular-section {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 32px;
    }

    .popular-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(0,0,0,0.05);
    }

    .popular-title i {
        font-size: 28px;
        color: #ff6d00;
    }

    .popular-title h3 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }

    .popular-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .popular-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px;
        background: rgba(255,255,255,0.5);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }

    .popular-item:hover {
        background: white;
        transform: translateX(5px);
    }

    .rank-number {
        width: 40px;
        height: 40px;
        background: var(--pastel-purple);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .rank-1 { background: #ffd700; color: #333; }
    .rank-2 { background: #c0c0c0; color: #333; }
    .rank-3 { background: #cd7f32; color: white; }

    .popular-info {
        flex: 1;
    }

    .popular-judul {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .popular-meta {
        font-size: 11px;
        color: var(--text-gray);
    }

    .popular-stats {
        font-size: 13px;
        font-weight: 600;
        color: var(--pastel-purple);
    }

    @media (max-width: 768px) {
        .page-header h1 { font-size: 24px; }
        .books-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
    }
</style>

<div class="page-header">
    <div class="container text-center">
        <h1><i class="fas fa-book me-2"></i>Katalog Buku Digital</h1>
        <p>Temukan koleksi buku terbaik untuk Anda</p>
    </div>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 16px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Popular Books Section -->
    @if(isset($popularBooks) && $popularBooks->count() > 0)
    <div class="popular-section">
        <div class="popular-title">
            <i class="fas fa-trophy"></i>
            <h3>Top {{ $popularBooks->count() }} Buku Paling Populer</h3>
        </div>
        <div class="popular-list">
            @foreach($popularBooks as $index => $book)
            <div class="popular-item">
                <div class="rank-number rank-{{ $index+1 }} @if($index+1 == 1) rank-1 @elseif($index+1 == 2) rank-2 @elseif($index+1 == 3) rank-3 @endif">
                    {{ $index+1 }}
                </div>
                <div class="popular-info">
                    <div class="popular-judul">{{ $book->judul }}</div>
                    <div class="popular-meta">
                        <i class="fas fa-user me-1"></i>{{ $book->penulis ?: 'Penulis tidak diketahui' }}
                        @if($book->penerbit)
                        | <i class="fas fa-building me-1"></i>{{ $book->penerbit }}
                        @endif
                    </div>
                    <div class="popular-meta">
                        <i class="fas fa-barcode me-1"></i>ISBN: {{ $book->isbn ?? 'Tidak tersedia' }}
                    </div>
                </div>
                <div class="popular-stats">
                    <i class="fas fa-chart-line me-1"></i>{{ $book->total_dipinjam ?? 0 }}x dipinjam
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="search-filter-section">
        <input type="text" id="searchInput" class="search-input" placeholder="🔍 Cari judul, penulis, atau penerbit...">
        <div class="filter-label"><i class="fas fa-tags me-2"></i>Filter Kategori</div>
        <div class="filter-buttons" id="filterButtons">
            <button class="filter-chip active" data-category="">Semua Kategori</button>
            @foreach($kategoris as $kategori)
                <button class="filter-chip" data-category="{{ $kategori->id }}">{{ $kategori->nama }}</button>
            @endforeach
        </div>
    </div>

    <!-- Books Section -->
    <div id="booksSection">
        <div class="books-grid">
            @foreach($books as $book)
            <div class="book-card">
                @if($book->gambar)
                    <img src="{{ asset('storage/' . $book->gambar) }}" class="book-cover" alt="{{ $book->judul }}">
                @else
                    <div class="book-cover"><i class="fas fa-book"></i></div>
                @endif
                <div class="book-info">
                    <h6 class="book-title">{{ Str::limit($book->judul, 40) }}</h6>
                    <p class="book-author">{{ Str::limit($book->penulis, 25) ?: 'Penulis tidak diketahui' }}</p>
                    <span class="book-category">{{ $book->kategori->nama ?? 'Tanpa Kategori' }}</span>
                </div>
            </div>
            @endforeach
        </div>

        @if($books->count() == 0)
        <div class="empty-state">
            <i class="fas fa-book-open fa-3x mb-3"></i>
            <h4>Belum Ada Buku</h4>
            <p>Belum ada buku yang tersedia di katalog</p>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($books->count() > 0)
    <div class="mt-4 d-flex justify-content-center">
        {{ $books->links() }}
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentCategory = '';
    let currentSearch = '';
    let searchTimeout;

    $(document).ready(function() {
        // Search input
        $('#searchInput').on('input', function() {
            clearTimeout(searchTimeout);
            currentSearch = $(this).val();
            searchTimeout = setTimeout(() => loadBooks(), 500);
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
                } else {
                    $('#booksSection').html(`
                        <div class="empty-state">
                            <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                            <h4>Error</h4>
                            <p>${response.message || 'Gagal memuat buku'}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#booksSection').html(`
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
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
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h4>Tidak Ada Buku</h4>
                    <p>${msg}</p>
                    ${(currentSearch || currentCategory) ? '<button class="btn-reset" onclick="resetFilters()">Reset Filter</button>' : ''}
                </div>
            `);
            return;
        }
        
        let html = `<div class="books-grid">`;
        
        books.forEach(book => {
            html += `
                <div class="book-card">
                    ${book.gambar ? `<img src="/storage/${book.gambar}" class="book-cover" alt="${escapeHtml(book.judul)}">` : 
                                    `<div class="book-cover"><i class="fas fa-book"></i></div>`}
                    <div class="book-info">
                        <h6 class="book-title">${escapeHtml(book.judul)}</h6>
                        <p class="book-author">${escapeHtml(book.penulis) || 'Penulis tidak diketahui'}</p>
                        <span class="book-category">${escapeHtml(book.kategori?.nama || 'Tanpa Kategori')}</span>
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