@extends('layouts.app')

@section('title', 'Rak Buku Digital')

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
    }

    .rak-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .rak-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border-radius: var(--radius);
        padding: 0;
        transition: var(--transition);
        cursor: pointer;
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .rak-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(179, 141, 255, 0.5);
    }

    .rak-card.selected {
        border: 3px solid var(--pastel-purple);
        box-shadow: 0 0 0 6px rgba(179, 141, 255, 0.2);
    }

    .rak-header {
        padding: 24px;
        background: linear-gradient(135deg, var(--pastel-blue), var(--pastel-purple));
    }

    .rak-header .rak-title {
        color: white;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .rak-header .rak-number {
        background: rgba(255,255,255,0.2);
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        color: white;
    }

    .rak-header .rak-desc {
        color: rgba(255,255,255,0.9);
        font-size: 13px;
        margin-top: 12px;
    }

    .rak-stats {
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        background: rgba(0,0,0,0.03);
        font-size: 13px;
        color: var(--text-gray);
    }

    .category-tag {
        display: inline-block;
        font-size: 11px;
        background: linear-gradient(135deg, var(--pastel-pink-light), var(--pastel-blue-light));
        padding: 4px 10px;
        border-radius: 50px;
        color: white;
        margin: 2px;
    }

    .search-filter-section {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 32px;
        display: none;
    }

    .search-filter-section.show {
        display: block;
        animation: fadeInUp 0.4s ease-out;
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

    @media (max-width: 768px) {
        .page-header h1 { font-size: 24px; }
        .rak-container { grid-template-columns: 1fr; }
        .books-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
    }
</style>

<div class="page-header">
    <div class="container text-center">
        <h1><i class="fas fa-layer-group me-2"></i>Rak Buku Digital</h1>
        <p>Klik rak untuk melihat koleksi buku di dalamnya</p>
    </div>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 16px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="rak-container" id="rakContainer">
        @forelse($raks as $rak)
            <div class="rak-card" 
                 data-rak-id="{{ $rak->id }}"
                 data-rak-judul="{{ $rak->judul }}"
                 data-rak-nomor="{{ $rak->nomor }}">
                <div class="rak-header">
                    <h3 class="rak-title">{{ $rak->judul }}</h3>
                    <span class="rak-number">Rak {{ $rak->nomor }}</span>
                    <p class="rak-desc">{{ Str::limit($rak->deskripsi, 80) ?: 'Koleksi buku berkualitas' }}</p>
                </div>
                <div class="rak-stats">
                    <span><i class="fas fa-book"></i> {{ $rak->total_buku ?? 0 }} Buku</span>
                    <span><i class="fas fa-tag"></i> {{ $rak->categories->count() }} Kategori</span>
                </div>
                <div class="px-3 pb-3">
                    @forelse($rak->categories->take(4) as $cat)
                        <span class="category-tag">{{ $cat->nama }}</span>
                    @empty
                        <span class="category-tag">Belum ada kategori</span>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1/-1;">
                <i class="fas fa-layer-group fa-3x mb-3"></i>
                <h4>Belum Ada Rak</h4>
                <p>Silahkan tambah rak terlebih dahulu</p>
            </div>
        @endforelse
    </div>

    <div class="search-filter-section" id="searchFilterSection">
        <input type="text" id="searchInput" class="search-input" placeholder="🔍 Cari judul atau penulis buku...">
        <div class="filter-label"><i class="fas fa-tags me-2"></i>Filter Kategori</div>
        <div class="filter-buttons" id="filterButtons"></div>
    </div>

    <div id="booksSection">
        @if($selectedRak && $books->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-book-open me-2"></i>Buku di {{ $selectedRak->judul }}</h4>
                <span class="badge bg-primary">{{ $books->count() }} Buku</span>
            </div>
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
                            <span class="book-category">{{ $book->category->nama ?? 'Tanpa Kategori' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($selectedRak)
            <div class="empty-state">
                <i class="fas fa-book-open fa-3x mb-3"></i>
                <h4>Belum Ada Buku</h4>
                <p>Belum ada buku yang masuk ke rak ini</p>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-hand-point-up fa-3x mb-3"></i>
                <h4>Pilih Rak</h4>
                <p>Klik salah satu rak di atas untuk melihat koleksi buku</p>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentRakId = null;
    let currentRakJudul = null;
    let currentCategory = 'all';
    let currentSearch = '';
    let searchTimeout;

    // Base URL untuk AJAX - TIDAK MENGGUNAKAN ROUTE DENGAN PARAMETER
    const booksAjaxUrl = "{{ url('/rak/books') }}";

    $(document).on('click', '.rak-card', function() {
        currentRakId = $(this).data('rak-id');
        currentRakJudul = $(this).data('rak-judul');
        currentCategory = 'all';
        currentSearch = '';
        $('#searchInput').val('');
        
        $('.rak-card').removeClass('selected');
        $(this).addClass('selected');
        $('#searchFilterSection').addClass('show');
        loadBooks();
    });

    function loadBooks() {
        if (!currentRakId) return;
        
        $('#booksSection').html('<div class="loading"><i class="fas fa-spinner fa-spin"></i><p>Memuat buku...</p></div>');
        
        $.ajax({
            url: booksAjaxUrl,
            method: 'GET',
            data: {
                rak_id: currentRakId,
                category_id: currentCategory,
                search: currentSearch
            },
            success: function(response) {
                if (response.success) {
                    displayBooks(response.books, response.rak);
                    updateFilters(response.books);
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

    function updateFilters(books) {
        let categories = new Set();
        books.forEach(book => {
            if (book.category_nama) categories.add(book.category_nama);
        });
        
        let html = '<button class="filter-chip active" data-category="all">📚 Semua Buku</button>';
        categories.forEach(cat => {
            html += `<button class="filter-chip" data-category="${cat}">📖 ${cat}</button>`;
        });
        $('#filterButtons').html(html);
        
        $('.filter-chip').off('click').on('click', function() {
            $('.filter-chip').removeClass('active');
            $(this).addClass('active');
            currentCategory = $(this).data('category');
            loadBooks();
        });
    }

    function displayBooks(books, rak) {
        if (!books || books.length === 0) {
            let msg = currentSearch ? `Tidak ditemukan "${currentSearch}"` : 
                      (currentCategory !== 'all' ? `Tidak ada buku kategori "${currentCategory}"` : 'Belum ada buku');
            $('#booksSection').html(`
                <div class="empty-state">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h4>Tidak Ada Buku</h4>
                    <p>${msg}</p>
                    ${(currentSearch || currentCategory !== 'all') ? '<button class="btn-reset" onclick="resetFilters()">Reset Filter</button>' : ''}
                </div>
            `);
            return;
        }
        
        let html = `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-book-open me-2"></i>Buku di ${escapeHtml(rak?.judul || currentRakJudul)}</h4>
                <span class="badge bg-primary">${books.length} Buku</span>
            </div>
            <div class="books-grid">
        `;
        
        books.forEach(book => {
            html += `
                <div class="book-card">
                    ${book.gambar ? `<img src="/storage/${book.gambar}" class="book-cover" alt="${escapeHtml(book.judul)}">` : 
                                    `<div class="book-cover"><i class="fas fa-book"></i></div>`}
                    <div class="book-info">
                        <h6 class="book-title">${escapeHtml(book.judul)}</h6>
                        <p class="book-author">${escapeHtml(book.penulis)}</p>
                        <span class="book-category">${escapeHtml(book.category_nama)}</span>
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
        currentCategory = 'all';
        currentSearch = '';
        $('#searchInput').val('');
        loadBooks();
    };

    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        currentSearch = $(this).val();
        searchTimeout = setTimeout(() => loadBooks(), 500);
    });
</script>
@endsection