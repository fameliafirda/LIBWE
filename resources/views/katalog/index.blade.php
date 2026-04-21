@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Katalog Produk</h1>

    @if($produk->count() > 0)
        <div class="row g-4">
            @foreach($produk as $item)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                             class="card-img-top" 
                             alt="{{ $item->nama_produk }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <span>No Image</span>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->nama_produk }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($item->deskripsi, 80) }}
                        </p>
                        <p class="card-text fw-bold text-primary">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Stok: {{ $item->stok }}</small>
                        </p>
                    </div>

                    <div class="card-footer bg-transparent border-top-0 pb-3">
                        <a href="{{ route('katalog.show', $item->id) }}" 
                           class="btn btn-primary w-100">
                           Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $produk->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            Belum ada produk tersedia.
        </div>
    @endif
</div>
@endsection