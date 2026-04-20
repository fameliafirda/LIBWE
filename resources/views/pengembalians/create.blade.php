@extends('layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Pengembalian Buku</div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengembalians.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="pinjaman_id">Pilih Peminjaman</label>
                    <select name="pinjaman_id" id="pinjaman_id" class="form-control" required>
                        <option value="">-- Pilih Peminjaman --</option>
                        @foreach($pinjamans as $pinjaman)
                            <option value="{{ $pinjaman->id }}">
                                {{ $pinjaman->nama }} - 
                                {{ $pinjaman->buku->judul ?? $pinjaman->judul_buku ?? '-' }} 
                                (Kembali: {{ $pinjaman->tanggal_kembali }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Catat Pengembalian</button>
                <a href="{{ route('pengembalians.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection