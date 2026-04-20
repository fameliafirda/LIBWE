@extends('layouts.app')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<style>
    /* ============================================================
       1. LAYOUT RESET (MEMBUANG SIDEBAR SECARA PAKSA)
       ============================================================ */
    /* Menghilangkan elemen sidebar berdasarkan class umum AdminLTE/Bootstrap */
    .main-sidebar, .sidebar, #sidebar-wrapper, .nav-sidebar { 
        display: none !important; 
    }

    /* Memaksa konten menjadi Full Width */
    .content-wrapper, .main-content, #page-content-wrapper { 
        margin-left: 0 !important; 
        width: 100% !important; 
        padding: 0 !important; 
        background: #0f0c29 !important; /* Biru Gelap ala Netflix */
        min-height: 100vh;
    }

    /* Menghilangkan navbar atas jika mengganggu layout full */
    /* .main-header { display: none !important; } */

    body { 
        background: #0f0c29; 
        color: #fff; 
        font-family: 'Inter', sans-serif; 
        overflow-x: hidden;
    }

    .container-custom { 
        max-width: 1300px; 
        margin: 0 auto; 
        padding: 0 40px; 
    }

    /* ============================================================
       2. HEADER SECTION
       ============================================================ */
    .header-section { 
        text-align: center; 
        padding: 70px 0 40px; 
        background: linear-gradient(to bottom, rgba(0,0,0,0.5), transparent);
    }
    
    .header-section h1 { 
        font-size: 48px; 
        font-weight: 900; 
        letter-spacing: -1px;
        background: linear-gradient(135deg, #d161ff, #ff9bc4);
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    .header-section p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 18px;
    }

    /* ============================================================
       3. TOP 10 RECOMMENDATION (HORIZONTAL SCROLL)
       ============================================================ */
    .top-container { 
        background: rgba(255,255,255,0.03); 
        border-radius: 24px; 
        padding: 30px; 
        margin-bottom: 50px; 
        border: 1px solid rgba(255,255,255,0.1); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .top-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 25px; 
    }

    .top-header h4 { 
        color: #ff9d76; 
        font-weight: 700; 
        font-size: 24px;
        margin: 0;
    }

    .horizontal-scroll { 
        display: flex; 
        gap: 25px; 
        overflow-x: auto; 
        padding: 10px 0 20px; 
        scroll-behavior: smooth;
    }

    .horizontal-scroll::-webkit-scrollbar { height: 6px; }
    .horizontal-scroll::-webkit-scrollbar-thumb { 
        background: rgba(209, 97, 255, 0.5); 
        border-radius: 10px; 
    }

    .top-card { 
        min-width: 180px; 
        position: relative; 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
    }

    .top-card:hover { transform: scale(1.1) translateY(-10px); z-index: 10; }

    .top-card img { 
        width: 180px; 
        height: 270px; 
        object-fit: cover; 
        border-radius: 15px; 
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
    }

    .rank-badge { 
        position: absolute; 
        top: -15px; 
        left: -15px; 
        background: linear-gradient(135deg, #ffcc00, #ff9500);
        color: #000; 
        font-weight: 900; 
        width: 45px; 
        height: 45px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 20px;
        box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
        z-index: 2;
        transform: rotate(-10deg);
    }

    /* ============================================================
       4. SEARCH BAR SECTION
       ============================================================ */
    .search-wrapper {
        max-width: 800px;
        margin: 0 auto 50px;
    }

    .search-bar { 
        background: rgba(255,255,255,0.08); 
        backdrop-filter: blur(10px);
        border-radius: 100px; 
        padding: 8px 10px 8px 30px; 
        display: flex; 
        gap: 15px; 
        align-items: center; 
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.3s;
    }

    .search-bar:focus-within {
        border-color: #d161ff;
        background: rgba(255,255,255,0.12);
        box-shadow: 0 0 20px rgba(209, 97, 255, 0.2);
    }

    .search-bar input { 
        background: transparent; 
        border: none; 
        color: white; 
        outline: none; 
        flex: 2; 
        font-size: 16px;
    }

    .search-bar select { 
        background: transparent; 
        border: none; 
        color: rgba(255,255,255,0.7); 
        outline: none; 
        flex: 1;
        border-left: 1px solid rgba(255,255,255,0.1);
        padding-left: 15px;
        cursor: pointer;
    }

    .btn-search { 
        background: linear-gradient(135deg, #a855f7, #d161ff); 
        border: none; 
        color: white; 
        padding: 12px 35px; 
        border-radius: 100px; 
        font-weight: 700; 
        transition: 0.3s;
    }

    .btn-search:hover { opacity: 0.9; transform: scale(1.05); }

    /* ============================================================
       5. MAIN BOOKS GRID
       ============================================================ */
    .books-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); 
        gap: 35px; 
        padding-bottom: 80px;
    }

    .book-item { 
        background: transparent;
        transition: 0.3s; 
    }

    .book-item:hover { transform: translateY(-12px); }

    .book-poster { 
        position: relative; 
        margin-bottom: 15px; 
        overflow: hidden;
        border-radius: 15px;
    }

    .book-poster img { 
        width: 100%; 
        height: 280px; 
        object-fit: cover; 
        transition: 0.5s;
    }

    .book-item:hover .book-poster img { transform: scale(1.1); }

    .stock-label { 
        position: absolute; 
        bottom: 12px; 
        right: 12px; 
        background: rgba(255, 153, 0, 0.95); 
        color: #000;
        padding: 4px 12px; 
        border-radius: 8px; 
        font-size: 12px; 
        font-weight: 800; 
    }

    .book-info h5 { 
        font-size: 16px; 
        font-weight: 700; 
        margin: 0 0 5px 0; 
        color: #fff; 
        line-height: 1.4;
    }

    .book-info p { 
        font-size: 14px; 
        color: rgba(255,255,255,0.5); 
        margin: 0;
    }

    /* Tombol Floating Back */
    .btn-floating-back { 
        position: fixed; 
        bottom: 40px; 
        left: 40px; 
        background: #fff; 
        color: #000; 
        padding: 15px 25px; 
        border-radius: 100px; 
        text-decoration: none !important; 
        font-weight: 800; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
        z-index: 1000; 
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }

    .btn-floating-back:hover { background: #d161ff; color: #fff; transform: translateX(10px); }

    /* Custom Pagination */
    .pagination .page-link { background: rgba(255,255,255,0.05); border: none; color: #fff; margin: 0 5px; border-radius: 8px; }
    .pagination .active .page-link { background: #d161ff; }
</style>

<a href="{{ url('/') }}" class="btn-floating-back">
    <i class="fas fa-arrow-left"></i> KEMBALI KE BERANDA
</a>

<div class="header-section">
    <div class="container-custom">
        <div class="d-flex justify-content-center align-items-center mb-2">
            <i class="fas fa-book-open fa-2x me-3 text-info"></i>
            <h1>Katalog Digital</h1>
        </div>
        <p>Temukan ribuan jendela dunia di Perpustakaan SDN Berat Wetan 1</p>
    </div>
</div>

<div class="container-custom">
    
    @if($popularBooks->count() > 0)
    <div class="top-container">
        <div class="top-header">
            <h4><i class="fas fa-bolt me-2 text-warning"></i> TOP {{ $popularBooks->count() }} PALING DIMINATI</h4>
            <div class="small text-muted border border-secondary px-3 py-1 rounded-pill">
                Update Otomatis
            </div>
        </div>
        <div class="horizontal-scroll">
            @foreach($popularBooks as $index => $pb)
            <div class="top-card">
                <div class="rank-badge">{{ $index + 1 }}</div>
                @if($pb->gambar)
                    <img src="{{ asset('storage/'.$pb->gambar) }}" alt="{{ $pb->judul }}">
                @else
                    <div style="width:180px; height:270px; background:#222; border-radius:15px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-book fa-3x text-secondary"></i>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="search-wrapper">
        <div class="search-bar">
            <i class="fas fa-search text-muted"></i>
            <input type="text" id="keyword" placeholder="Cari judul buku, penulis, atau penerbit...">
            <select id="kat_id">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
            <button class="btn-search" onclick="filterBuku()">
                CARI BUKU
            </button>
        </div>
    </div>

    <div id="grid-container">
        <div class="books-grid">
            @forelse($books as $book)
            <div class="book-item">
                <div class="book-poster">
                    @if($book->gambar)
                        <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                    @else
                        <div style="height:280px; background:#222; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    @endif
                    <div class="stock-label">STOK: {{ $book->stok }}</div>
                </div>
                <div class="book-info">
                    <h5>{{ Str::limit($book->judul, 40) }}</h5>
                    <p>{{ $book->penulis ?? 'Penulis Anonim' }}</p>
                    <div class="mt-2">
                        <span class="badge bg-secondary" style="font-size: 10px; opacity: 0.7;">
                            {{ $book->kategori->nama ?? 'Umum' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center w-100 py-5">
                <i class="fas fa-ghost fa-3x mb-3 text-muted"></i>
                <p>Maaf, koleksi buku belum tersedia.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-2 d-flex justify-content-center pb-5">
            {{ $books->links() }}
        </div>
    </div>
</div>

<script>
function filterBuku() {
    let keyword = document.getElementById('keyword').value;
    let kategori = document.getElementById('kat_id').value;
    let container = document.getElementById('grid-container');

    // Beri efek loading sederhana
    container.style.opacity = '0.4';

    fetch(`{{ route('katalog.filter') }}?search=${keyword}&kategori=${kategori}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let html = '<div class="books-grid">';
                
                if (data.books.length === 0) {
                    html = '<div class="text-center w-100 py-5"><p>Buku tidak ditemukan.</p></div>';
                } else {
                    data.books.forEach(b => {
                        let imgUrl = b.gambar ? `/storage/${b.gambar}` : 'https://via.placeholder.com/190x280?text=No+Image';
                        let kategoriNama = b.kategori ? b.kategori.nama : 'Umum';
                        
                        html += `
                        <div class="book-item">
                            <div class="book-poster">
                                <img src="${imgUrl}" alt="${b.judul}">
                                <div class="stock-label">STOK: ${b.stok}</div>
                            </div>
                            <div class="book-info">
                                <h5>${b.judul}</h5>
                                <p>${b.penulis || 'Penulis Anonim'}</p>
                                <div class="mt-2">
                                    <span class="badge bg-secondary" style="font-size: 10px; opacity: 0.7;">
                                        ${kategoriNama}
                                    </span>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                
                html += '</div>';
                container.innerHTML = html;
            }
            container.style.opacity = '1';
        })
        .catch(err => {
            console.error(err);
            container.style.opacity = '1';
        });
}

// Fitur Enter untuk mencari
document.getElementById('keyword').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        filterBuku();
    }
});
</script>

@endsection