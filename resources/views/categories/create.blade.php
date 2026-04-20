@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tambah Kategori</h3>
  </div>

  <div class="card-body">
    <form action="{{ route('categories.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="nama" class="form-label">Nama Kategori</label>
        <input type="text" name="nama" id="nama" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
