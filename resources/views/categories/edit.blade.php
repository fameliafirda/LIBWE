@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Kategori</h3>
  </div>

  <div class="card-body">
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="nama" class="form-label">Nama Kategori</label>
        <input type="text" name="nama" id="nama" class="form-control" value="{{ $category->nama }}" required>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
