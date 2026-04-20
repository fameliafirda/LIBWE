@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Buku</h3>
  </div>
  <div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $book->judul) }}" required>
      </div>

      <div class="mb-3">
        <label for="penulis" class="form-label">Penulis</label>
        <input type="text" name="penulis" id="penulis" class="form-control" value="{{ old('penulis', $book->penulis) }}" required>
      </div>
      
      <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ old('penerbit', $book->penerbit) }}">
      </div>

      <div class="mb-3">
        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" required>
      </div>
      
      {{-- INPUT STOK BARU --}}
      <div class="mb-3">
        <label for="stok" class="form-label">Stok Buku</label>
        <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', $book->stok) }}" min="0" required>
        <small class="text-muted">Jumlah buku yang tersedia</small>
      </div>

      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori</label>
        <select name="kategori_id" id="kategori_id" class="form-control" required>
          @foreach($categories as $kategori)
            <option value="{{ $kategori->id }}" {{ $kategori->id == old('kategori_id', $book->kategori_id) ? 'selected' : '' }}>
              {{ $kategori->nama }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="gambar" class="form-label">Gambar Buku</label>
        @if($book->gambar)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $book->gambar) }}" width="100" alt="Current image">
                <p class="text-muted small">Gambar saat ini</p>
            </div>
        @endif
        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection