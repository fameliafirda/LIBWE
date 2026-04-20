@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tambah Buku</h3>
  </div>

  <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Terjadi kesalahan!</strong>
          <ul class="mt-2 mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="mb-3">
        <label for="judul" class="form-label">Judul Buku</label>
        <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
        @error('judul')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="mb-3">
        <label for="penulis" class="form-label">Penulis</label>
        <input type="text" name="penulis" id="penulis" class="form-control" value="{{ old('penulis') }}" required>
        @error('penulis')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      
      <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ old('penerbit') }}">
        @error('penerbit')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="mb-3">
        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit') }}" required>
        @error('tahun_terbit')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      {{-- INPUT STOK BARU --}}
      <div class="mb-3">
        <label for="stok" class="form-label">Stok Buku</label>
        <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', 0) }}" min="0" required>
        <small class="text-muted">Jumlah buku yang tersedia</small>
        @error('stok')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori</label>
        <select name="kategori_id" id="kategori_id" class="form-control" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($categories as $kategori)
            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
              {{ $kategori->nama }}
            </option>
          @endforeach
        </select>
        @error('kategori_id')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="mb-3">
        <label for="gambar" class="form-label">Gambar Buku</label>
        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
        @error('gambar')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
@endsection