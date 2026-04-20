@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Anggota</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('anggotas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <button class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>
@endsection
