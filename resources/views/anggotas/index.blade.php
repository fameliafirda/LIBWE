@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0 20px 20px 0;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-2" style="font-weight: 600;">
                                <i class="fas fa-users me-2"></i> Data Anggota Perpustakaan
                            </h3>
                            <p class="text-white opacity-75 mb-0">Kelola data anggota perpustakaan SDN Berat Wetan 1</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('anggotas.create') }}" class="btn btn-light" style="border-radius: 50px; padding: 10px 25px; font-weight: 500; color: #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-user-plus me-2"></i> Tambah Anggota
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #28a745;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>Filter Kelas:
                            </label>
                            <form method="GET" action="{{ route('anggotas.index') }}" class="d-inline">
                                <select name="kelas" onchange="this.form.submit()" class="form-control" style="max-width: 300px; border-radius: 50px; border: 1px solid #e0e0e0;">
                                    <option value="">-- Semua Kelas --</option>
                                    @if(!empty($daftar_kelas))
                                        @foreach ($daftar_kelas as $k)
                                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </form>
                            @if(request('kelas'))
                                <a href="{{ route('anggotas.index') }}" class="btn btn-sm ms-3" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @endif
                        </div>

                        @if(count($anggotas) > 0)
                        <button type="button" 
                                class="btn btn-danger" 
                                style="border-radius: 50px; padding: 8px 25px; background: linear-gradient(135deg, #ff4757, #ff6b81); border: none;"
                                onclick="confirmDeleteAll({{ count($anggotas) }})">
                            <i class="fas fa-user-slash me-2"></i> Hapus Semua Anggota ({{ count($anggotas) }})
                        </button>
                        
                        <form id="deleteAllForm" action="{{ route('anggotas.delete-all') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                            <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Anggota
                            <span class="badge ms-2" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">{{ count($anggotas) }} anggota</span>
                        </h5>
                        
                        <div class="input-group" style="width: 100%; max-width: 300px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari anggota..." id="searchInput" style="border-radius: 50px 0 0 50px; border: 1px solid #e0e0e0;">
                            <button class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); border-radius: 0 50px 50px 0; color: #000;" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-nowrap" style="min-width: 1200px;" id="anggotaTable">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 100px;">NISN</th>
                                    <th style="width: 150px;">Nama</th>
                                    <th style="width: 100px;">Kelas</th>
                                    <th style="width: 120px;">Jenis Kelamin</th>
                                    <th>Buku yang Dipinjam</th>
                                    <th style="width: 120px;" class="text-center">Total Denda</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($anggotas as $item)
                                <tr style="vertical-align: middle;" id="row-{{ $item['anggota']->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    
                                    <td>
                                        <span class="badge bg-secondary px-2 py-1">{{ $item['anggota']->nisn ?? '-' }}</span>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="color: #8b5cf6;"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $item['anggota']->nama }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark px-3 py-2">{{ $item['anggota']->kelas }}</span>
                                    </td>
                                    <td>
                                        @if($item['anggota']->jenis_kelamin == 'Laki-laki')
                                            <span class="badge bg-info text-white px-3 py-2">
                                                <i class="fas fa-mars me-1"></i> Laki-laki
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2">
                                                <i class="fas fa-venus me-1"></i> Perempuan
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['pinjamans']->count() > 0)
                                            <div style="max-height: 100px; overflow-y: auto; padding-right: 5px;">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($item['pinjamans'] as $peminjaman)
                                                        <li class="mb-2 pb-2 border-bottom">
                                                            <div class="d-flex align-items-start gap-2">
                                                                <i class="fas fa-book mt-1" style="color: #8b5cf6; font-size: 12px;"></i>
                                                                <div>
                                                                    <span class="fw-semibold">{{ $peminjaman->judul_buku ?? 'Buku tidak tersedia' }}</span>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}
                                                                        @if($peminjaman->tanggal_kembali)
                                                                            - {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                                                                        @else
                                                                            (Belum dikembalikan)
                                                                        @endif
                                                                    </small>
                                                                    <br>
                                                                    @if($peminjaman->status == 'belum dikembalikan')
                                                                        <span class="badge bg-warning text-dark mt-1">Dipinjam</span>
                                                                    @else
                                                                        <span class="badge bg-success mt-1">Dikembalikan</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">
                                                <i class="fas fa-info-circle me-1"></i>Belum ada peminjaman
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item['pinjamans']->sum('denda') > 0)
                                            <span class="fw-bold" style="color: #e74c3c;">
                                                Rp {{ number_format($item['pinjamans']->sum('denda'), 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="badge bg-success px-3 py-2">Rp 0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 25px;">
                                            <a href="{{ route('anggotas.edit', $item['anggota']->id) }}" 
                                               style="color: #f59e0b; font-size: 1.4rem; transition: 0.2s; text-decoration: none;"
                                               onmouseover="this.style.transform='scale(1.2)'"
                                               onmouseout="this.style.transform='scale(1)'"
                                               title="Edit anggota">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('anggotas.peminjaman', $item['anggota']->id) }}" 
                                               style="color: #10b981; font-size: 1.4rem; transition: 0.2s; text-decoration: none;"
                                               onmouseover="this.style.transform='scale(1.2)'"
                                               onmouseout="this.style.transform='scale(1)'"
                                               title="Riwayat peminjaman">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    style="background: transparent; border: none; color: #ef4444; font-size: 1.4rem; transition: 0.2s; cursor: pointer; padding: 0;"
                                                    onmouseover="this.style.transform='scale(1.2)'"
                                                    onmouseout="this.style.transform='scale(1)'"
                                                    onclick="confirmDelete({{ $item['anggota']->id }}, '{{ addslashes($item['anggota']->nama) }}', {{ $item['pinjamans']->where('status', 'belum dikembalikan')->count() }})"
                                                    title="Hapus anggota">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-4x mb-3" style="color: #dfe6e9;"></i>
                                            <h6>Belum ada data anggota</h6>
                                            <p class="small mb-3">Silakan tambah anggota baru</p>
                                            <a href="{{ route('anggotas.create') }}" class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">
                                                <i class="fas fa-user-plus me-1"></i> Tambah Anggota
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    td::-webkit-scrollbar { width: 4px; }
    td::-webkit-scrollbar-track { background: #f1f1f1; }
    td::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #f7c0ec, #a7bdea); border-radius: 10px; }
    td::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #ec4899, #8b5cf6); }
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(id, nama, pinjamanAktif) {
        let message = `⚠️ HAPUS ANGGOTA\n\nNama: ${nama}\n\n`;
        if (pinjamanAktif > 0) message += `PERHATIAN! Memiliki ${pinjamanAktif} buku belum dikembalikan!\n\n`;
        message += `Lanjutkan?`;
        if (confirm(message)) {
            document.getElementById('deleteForm').action = `{{ url('anggotas') }}/${id}`;
            document.getElementById('deleteForm').submit();
        }
    }

    function confirmDeleteAll(jumlah) {
        let message = `⚠️ HAPUS SEMUA ANGGOTA (${jumlah})\n\nKetik 'HAPUS' untuk melanjutkan:`;
        let konfirmasi = prompt(message);
        if (konfirmasi === 'HAPUS') {
            document.getElementById('deleteAllForm').submit();
        }
    }

    document.getElementById('searchButton').addEventListener('click', filterTable);
    document.getElementById('searchInput').addEventListener('keyup', (e) => { if(e.key === 'Enter') filterTable(); });

    function filterTable() {
        let searchValue = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#anggotaTable tbody tr').forEach(row => {
            if (row.querySelector('td[colspan="8"]')) return; // Update colspan karena tambah kolom
            row.style.display = row.textContent.toLowerCase().includes(searchValue) ? '' : 'none';
        });
    }
</script>
@endpush