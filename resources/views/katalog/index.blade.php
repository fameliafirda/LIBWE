<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Katalog Buku LIBWE - Dengan Rekomendasi Populer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #8b5cf6;
      --primary-dark: #7c3aed;
      --pink: #ec4899;
      --blue: #3b82f6;
      --dark: #0f172a;
      --darker: #020617;
      --card-bg: rgba(15, 23, 42, 0.8);
      --text: #e2e8f0;
      --text-secondary: #94a3b8;
      --border: rgba(255, 255, 255, 0.08);
      --border-focus: rgba(139, 92, 246, 0.5);
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --glow: 0 0 30px rgba(139, 92, 246, 0.3);
      --gold: #fbbf24;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 100%);
      color: var(--text);
      min-height: 100vh;
    }

    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, var(--pink), var(--primary));
      border-radius: 10px;
    }

    /* Sticky Header */
    .sticky-header {
      position: sticky;
      top: 0;
      z-index: 100;
      background: linear-gradient(135deg, rgba(2, 6, 23, 0.95), rgba(15, 23, 42, 0.95));
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 20px 0;
      margin-bottom: 30px;
    }

    .header-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--pink), var(--primary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 5px;
      text-align: center;
    }

    .header-subtitle {
      color: var(--text-secondary);
      text-align: center;
      font-size: 0.9rem;
      letter-spacing: 1px;
    }

    /* Recommendation Section */
    .recommendation-wrapper {
      margin-bottom: 50px;
    }

    .recommendation-container {
      background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(236, 72, 153, 0.05));
      border-radius: 30px;
      padding: 30px 25px;
      border: 1px solid rgba(139, 92, 246, 0.2);
      position: relative;
      overflow: hidden;
    }

    .recommendation-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--pink), var(--primary), var(--gold));
    }

    .recommendation-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 25px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .recommendation-title-section {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .recommendation-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, rgba(236, 72, 153, 0.2), rgba(139, 92, 246, 0.2));
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
    }

    .recommendation-icon i {
      background: linear-gradient(135deg, var(--gold), var(--pink));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .recommendation-title {
      font-size: 1.6rem;
      font-weight: 700;
      font-family: 'Playfair Display', serif;
      margin: 0;
      background: linear-gradient(135deg, var(--gold), var(--pink));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .recommendation-subtitle {
      color: var(--text-secondary);
      font-size: 0.8rem;
      margin-top: 5px;
    }

    .recommendation-badge {
      background: rgba(251, 191, 36, 0.15);
      border: 1px solid rgba(251, 191, 36, 0.3);
      border-radius: 50px;
      padding: 8px 18px;
      font-size: 0.8rem;
      color: var(--gold);
    }

    /* Horizontal Scroll */
    .recommendation-scroll {
      position: relative;
    }

    .recommendation-track {
      display: flex;
      gap: 20px;
      overflow-x: auto;
      scroll-behavior: smooth;
      padding: 10px 5px 20px 5px;
      scrollbar-width: thin;
    }

    .recommendation-track::-webkit-scrollbar {
      height: 5px;
    }

    .recommendation-track::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
    }

    .recommendation-track::-webkit-scrollbar-thumb {
      background: linear-gradient(90deg, var(--pink), var(--primary));
      border-radius: 10px;
    }

    .rec-card {
      flex: 0 0 200px;
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border);
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
    }

    .rec-card:hover {
      transform: translateY(-5px);
      border-color: var(--gold);
      box-shadow: 0 15px 30px rgba(251, 191, 36, 0.15);
    }

    .rank-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, var(--gold), #f59e0b);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1rem;
      color: var(--darker);
      z-index: 2;
    }

    .rank-badge.rank-1 { background: linear-gradient(135deg, #ffd700, #ff8c00); }
    .rank-badge.rank-2 { background: linear-gradient(135deg, #e0e0e0, #b0b0b0); }
    .rank-badge.rank-3 { background: linear-gradient(135deg, #cd7f32, #b87333); }

    .hot-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(239, 68, 68, 0.9);
      color: white;
      padding: 4px 10px;
      border-radius: 50px;
      font-size: 0.7rem;
      font-weight: 600;
      z-index: 2;
    }

    .popular-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(245, 158, 11, 0.9);
      color: white;
      padding: 4px 10px;
      border-radius: 50px;
      font-size: 0.7rem;
      font-weight: 600;
      z-index: 2;
    }

    .rec-cover {
      position: relative;
      padding-top: 140%;
      overflow: hidden;
      background: linear-gradient(45deg, var(--darker), var(--dark));
    }

    .rec-cover-img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .rec-card:hover .rec-cover-img {
      transform: scale(1.08);
    }

    .rec-info {
      padding: 12px;
    }

    .rec-title {
      font-weight: 600;
      font-size: 0.85rem;
      margin-bottom: 4px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 38px;
    }

    .rec-author {
      font-size: 0.7rem;
      color: var(--text-secondary);
      margin-bottom: 8px;
    }

    .rec-stats {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.7rem;
    }

    .borrow-count {
      background: rgba(251, 191, 36, 0.15);
      color: var(--gold);
      padding: 3px 8px;
      border-radius: 50px;
    }

    .scroll-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      background: rgba(139, 92, 246, 0.8);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .scroll-btn:hover {
      background: var(--primary);
      transform: translateY(-50%) scale(1.1);
    }

    .scroll-left { left: -20px; }
    .scroll-right { right: -20px; }

    /* Section Divider */
    .section-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 30px 0 40px;
      gap: 15px;
    }

    .divider-line {
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--border), transparent);
    }

    .divider-icon {
      width: 40px;
      height: 40px;
      background: rgba(139, 92, 246, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-secondary);
    }

    /* Sticky Filter */
    .sticky-filter {
      position: sticky;
      top: 120px;
      z-index: 99;
      margin-bottom: 30px;
    }

    .filter-card {
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 20px;
      box-shadow: var(--glow);
    }

    .filter-input, .filter-select {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 12px 20px;
      color: var(--text);
      width: 100%;
    }

    .filter-input:focus, .filter-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--border-focus);
    }

    .btn-search {
      background: linear-gradient(135deg, var(--pink), var(--primary));
      border: none;
      border-radius: 50px;
      padding: 12px 30px;
      color: white;
      font-weight: 500;
      width: 100%;
    }

    .btn-reset {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 8px 20px;
      color: var(--text);
      text-decoration: none;
    }

    /* Book Cards */
    .book-grid {
      margin-top: 30px;
    }

    .book-card {
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border);
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.3s ease;
      height: 100%;
    }

    .book-card:hover {
      transform: translateY(-5px);
      border-color: var(--primary);
    }

    .cover-container {
      position: relative;
      padding-top: 140%;
      overflow: hidden;
      background: linear-gradient(45deg, var(--darker), var(--dark));
    }

    .cover-img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .book-info {
      padding: 15px;
    }

    .book-title {
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 5px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .book-author {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }

    .book-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
    }

    .book-category {
      background: linear-gradient(135deg, rgba(236, 72, 153, 0.2), rgba(139, 92, 246, 0.2));
      padding: 4px 12px;
      border-radius: 50px;
      font-size: 0.7rem;
    }

    .stock-available { background: rgba(16, 185, 129, 0.2); color: var(--success); }
    .stock-low { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
    .stock-empty { background: rgba(239, 68, 68, 0.2); color: var(--danger); }

    /* Pagination */
    .pagination {
      justify-content: center;
      margin-top: 40px;
      gap: 5px;
    }

    .page-item .page-link {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border);
      border-radius: 12px !important;
      color: var(--text);
    }

    .page-item.active .page-link {
      background: linear-gradient(135deg, var(--pink), var(--primary));
      color: white;
    }

    /* Floating Back Button */
    .btn-back {
      position: fixed;
      bottom: 30px;
      left: 30px;
      background: linear-gradient(135deg, var(--pink), var(--primary));
      color: white;
      padding: 12px 30px;
      border-radius: 50px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 1000;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
      .rec-card { flex: 0 0 160px; }
      .scroll-left { left: -10px; }
      .scroll-right { right: -10px; }
      .recommendation-title { font-size: 1.2rem; }
      .recommendation-icon { width: 40px; height: 40px; font-size: 1.3rem; }
      .btn-back { bottom: 20px; left: 20px; padding: 10px 20px; font-size: 0.9rem; }
    }
  </style>
</head>
<body>

<!-- Sticky Header -->
<div class="sticky-header">
  <div class="container">
    <h1 class="header-title">
      <i class="fas fa-book-open me-3"></i>Katalog Buku
    </h1>
    <p class="header-subtitle">Koleksi Buku Perpustakaan SDN Berat Wetan 1</p>
  </div>
</div>

<div class="container">
  
  <!-- ==================== REKOMENDASI BUKU POPULER ==================== -->
  @if(isset($popularBooks) && $popularBooks && $popularBooks->count() > 0)
  <div class="recommendation-wrapper">
    <div class="recommendation-container">
      <div class="recommendation-header">
        <div>
          <div class="recommendation-title-section">
            <div class="recommendation-icon">
              <i class="fas fa-trophy"></i>
            </div>
            <div>
              <h2 class="recommendation-title">Top 10 Paling Populer</h2>
              <p class="recommendation-subtitle">Buku yang paling sering dipinjam murid (diurutkan dari tertinggi)</p>
            </div>
          </div>
        </div>
        <div class="recommendation-badge">
          <i class="fas fa-chart-line"></i> Berdasarkan data peminjaman real
        </div>
      </div>

      <div class="recommendation-scroll" style="position: relative;">
        <div class="scroll-btn scroll-left" onclick="scrollRecommendations(-1)">
          <i class="fas fa-chevron-left"></i>
        </div>
        <div class="recommendation-track" id="recommendationTrack">
          @foreach($popularBooks as $index => $book)
          <div class="rec-card" onclick="searchByCategory({{ $book->kategori_id ?? 'null' }})">
            <div class="rank-badge @if($index == 0) rank-1 @elseif($index == 1) rank-2 @elseif($index == 2) rank-3 @endif">
              #{{ $index + 1 }}
            </div>
            @php
              $totalDipinjam = $book->total_dipinjam ?? 0;
            @endphp
            @if($totalDipinjam >= 20)
            <div class="hot-badge"><i class="fas fa-fire"></i> HOT</div>
            @elseif($totalDipinjam >= 10)
            <div class="popular-badge"><i class="fas fa-chart-line"></i> POPULER</div>
            @endif
            <div class="rec-cover">
              @if($book->gambar)
                <img src="{{ asset('storage/' . $book->gambar) }}" class="rec-cover-img" alt="{{ $book->judul }}">
              @else
                <div class="rec-cover-img" style="display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-book fa-3x" style="color: var(--text-secondary); opacity: 0.3;"></i>
                </div>
              @endif
            </div>
            <div class="rec-info">
              <div class="rec-title">{{ Str::limit($book->judul, 40) }}</div>
              <div class="rec-author">{{ Str::limit($book->penulis, 25) }}</div>
              <div class="rec-stats">
                <span class="borrow-count">
                  <i class="fas fa-users"></i> {{ number_format($totalDipinjam) }} x dipinjam
                </span>
                <span style="font-size: 0.65rem; color: var(--text-secondary);">
                  <i class="fas fa-tag"></i> {{ $book->kategori->nama ?? 'Umum' }}
                </span>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="scroll-btn scroll-right" onclick="scrollRecommendations(1)">
          <i class="fas fa-chevron-right"></i>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Section Divider -->
  <div class="section-divider">
    <div class="divider-line"></div>
    <div class="divider-icon">
      <i class="fas fa-book"></i>
    </div>
    <div class="divider-line"></div>
  </div>

  <!-- Sticky Filter Section (KATALOG BUKU) -->
  <div class="sticky-filter">
    <div class="filter-card">
      <form method="GET" action="{{ route('katalog') }}" id="filterForm">
        <div class="row g-3">
          <div class="col-md-6">
            <input type="text" class="filter-input" name="search" 
                   placeholder="Cari judul atau penulis..." 
                   value="{{ request('search') }}">
          </div>
          <div class="col-md-4">
            <select class="filter-select" name="kategori" id="categorySelect">
              <option value="">Semua Kategori</option>
              @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                  {{ $kategori->nama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn-search">
              <i class="fas fa-search me-2"></i>Cari
            </button>
          </div>
        </div>
      </form>

      @if(request('search') || request('kategori'))
      <div class="active-filters mt-3" style="display: flex; gap: 10px; flex-wrap: wrap;">
        @if(request('search'))
        <span class="filter-tag" style="background: rgba(139,92,246,0.2); border-radius: 50px; padding: 5px 15px;">
          <i class="fas fa-search me-1"></i> "{{ request('search') }}"
          <a href="{{ route('katalog', array_merge(request()->except('search'), ['kategori' => request('kategori')])) }}" class="text-decoration-none ms-2">✕</a>
        </span>
        @endif
        @if(request('kategori'))
        <span class="filter-tag" style="background: rgba(139,92,246,0.2); border-radius: 50px; padding: 5px 15px;">
          <i class="fas fa-tag me-1"></i> {{ $kategoris->firstWhere('id', request('kategori'))->nama ?? 'Kategori' }}
          <a href="{{ route('katalog', array_merge(request()->except('kategori'), ['search' => request('search')])) }}" class="text-decoration-none ms-2">✕</a>
        </span>
        @endif
        <a href="{{ route('katalog') }}" class="btn-reset">Reset Semua</a>
      </div>
      @endif

      <div class="results-info mt-3">
        <i class="fas fa-book me-1"></i> Total {{ $books->total() }} buku
      </div>
    </div>
  </div>

  <!-- Loading Spinner -->
  <div class="loading-spinner" id="loadingSpinner" style="display: none; text-align: center; padding: 40px;">
    <div class="spinner" style="width: 50px; height: 50px; border: 3px solid rgba(139,92,246,0.2); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
    <p class="mt-2">Memuat buku...</p>
  </div>

  <!-- Book Grid (KATALOG) -->
  <div class="row g-4 book-grid" id="bookGrid">
    @if($books->count() > 0)
      @foreach ($books as $book)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <div class="book-card">
            <div class="cover-container">
              @if($book->gambar)
                <img src="{{ asset('storage/' . $book->gambar) }}" class="cover-img" alt="{{ $book->judul }}">
              @else
                <div class="cover-img" style="display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-book fa-3x" style="color: var(--text-secondary); opacity: 0.3;"></i>
                </div>
              @endif
            </div>
            <div class="book-info">
              <div class="book-title">{{ Str::limit($book->judul, 50) }}</div>
              <div class="book-author">{{ Str::limit($book->penulis, 30) }}</div>
              <div class="book-meta">
                <span class="book-category">{{ $book->kategori->nama ?? 'Umum' }}</span>
                @if($book->stok > 5)
                  <span class="stock-badge stock-available" style="padding: 4px 8px; border-radius: 50px; font-size: 0.7rem;">{{ $book->stok }} Tersedia</span>
                @elseif($book->stok > 0)
                  <span class="stock-badge stock-low" style="padding: 4px 8px; border-radius: 50px; font-size: 0.7rem;">Sisa {{ $book->stok }}</span>
                @else
                  <span class="stock-badge stock-empty" style="padding: 4px 8px; border-radius: 50px; font-size: 0.7rem;">Stok Habis</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="col-12">
        <div class="empty-state text-center p-5" style="background: var(--card-bg); border-radius: 30px;">
          <i class="fas fa-book-open fa-3x mb-3" style="color: var(--text-secondary);"></i>
          <h4>Tidak Ada Buku Ditemukan</h4>
          <p>Silakan coba kata kunci atau kategori lain</p>
        </div>
      </div>
    @endif
  </div>

  <!-- Pagination -->
  @if(method_exists($books, 'links') && $books->hasPages())
    <div class="row mt-5">
      <div class="col-12">
        {{ $books->withQueryString()->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>

<!-- Floating Back Button -->
<a href="{{ url('/') }}" class="btn-back" id="backButton">
  <i class="fas fa-arrow-left"></i>
  <span>Kembali ke Beranda</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Scroll horizontal untuk rekomendasi
  function scrollRecommendations(direction) {
    const track = document.getElementById('recommendationTrack');
    if (track) {
      const scrollAmount = 280;
      track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
  }

  // Pencarian berdasarkan kategori (ketika card rekomendasi diklik)
  window.searchByCategory = function(categoryId) {
    if (categoryId) {
      const categorySelect = document.getElementById('categorySelect');
      if (categorySelect) {
        categorySelect.value = categoryId;
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
          filterForm.submit();
        }
      }
    }
  }

  // Animasi scroll untuk book cards
  function initCardAnimations() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.book-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.5s ease';
      observer.observe(card);
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    initCardAnimations();

    // Filter form handling
    const filterForm = document.getElementById('filterForm');
    const categorySelect = document.getElementById('categorySelect');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const bookGrid = document.getElementById('bookGrid');

    function submitForm() {
      if (loadingSpinner) loadingSpinner.style.display = 'block';
      if (bookGrid) bookGrid.style.opacity = '0.5';
      setTimeout(() => { if (filterForm) filterForm.submit(); }, 300);
    }

    if (categorySelect) {
      categorySelect.addEventListener('change', submitForm);
    }

    // Back button transition
    const backButton = document.getElementById('backButton');
    if (backButton) {
      backButton.addEventListener('click', function(e) {
        e.preventDefault();
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.3s ease';
        setTimeout(() => { window.location.href = this.href; }, 300);
      });
    }
  });
</script>

<style>
  .stock-badge {
    padding: 4px 8px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
  }
  .active-filters .filter-tag {
    background: rgba(139, 92, 246, 0.2);
    border-radius: 50px;
    padding: 5px 15px;
    font-size: 0.85rem;
  }
  .btn-reset {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 8px 20px;
    color: var(--text);
    text-decoration: none;
    display: inline-block;
  }
  .btn-reset:hover {
    background: rgba(255, 255, 255, 0.15);
    color: var(--text);
  }
  .empty-state {
    text-align: center;
    padding: 60px 20px;
  }
</style>

</body>
</html>