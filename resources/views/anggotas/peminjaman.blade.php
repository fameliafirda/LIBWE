@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Anggota')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Peminjaman - {{ $anggota->nama }}</h3>
        <a href="{{ route('anggotas.index') }}" class="btn btn-secondary float-right">Kembali</a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nama:</strong> {{ $anggota->nama }}
            </div>
            <div class="col-md-6">
                <strong>Kelas:</strong> {{ $anggota->kelas }}
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pinjamans as $pinjaman)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $pinjaman->buku->judul ?? $pinjaman->judul_buku ?? 'Buku tidak tersedia' }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ $pinjaman->tanggal_kembali ? \Carbon\Carbon::parse($pinjaman->tanggal_kembali)->format('d-m-Y') : '-' }}</td>
                    <td>
                        @if($pinjaman->status == 'belum dikembalikan')
                            <span class="badge badge-warning">Dipinjam</span>
                        @else
                            <span class="badge badge-success">Dikembalikan</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($pinjaman->denda ?? 0, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total Denda:</th>
                    <th>Rp {{ number_format($pinjamans->sum('denda'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection