@extends('layouts.app')

@section('title', '')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Data Peminjaman</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada kesalahan input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pinjamans.update', $pinjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $pinjaman->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $pinjaman->kelas) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $pinjaman->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $pinjaman->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul_buku" class="form-control" value="{{ old('judul_buku', $pinjaman->judul_buku) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', optional($pinjaman->tanggal_kembali)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="belum dikembalikan" {{ old('status', $pinjaman->status) == 'belum dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                <option value="sudah dikembalikan" {{ old('status', $pinjaman->status) == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pinjamans.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
