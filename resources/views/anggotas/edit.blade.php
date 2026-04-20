@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Anggota</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Ada kesalahan pada input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('anggotas.update', $anggota->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $anggota->nama) }}" required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $anggota->kelas) }}" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('anggotas.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
