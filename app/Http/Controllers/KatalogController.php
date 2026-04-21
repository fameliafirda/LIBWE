<!-- Di bagian rekomendasi, pastikan menggunakan @foreach dengan urutan yang sudah benar -->
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
                        <h2 class="recommendation-title">Paling Populer</h2>
                        <p class="recommendation-subtitle">Top 10 Buku yang paling sering dipinjam murid</p>
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
                    @if(($book->total_dipinjam ?? 0) >= 20)
                    <div class="hot-badge"><i class="fas fa-fire"></i> HOT</div>
                    @elseif(($book->total_dipinjam ?? 0) >= 10)
                    <div class="hot-badge" style="background: rgba(245, 158, 11, 0.9);"><i class="fas fa-chart-line"></i> POPULER</div>
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
                                <i class="fas fa-users"></i> {{ $book->total_dipinjam ?? 0 }} x dipinjam
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