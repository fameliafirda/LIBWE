@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Gradient -->
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

    <!-- Alert Messages -->
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

    <!-- Filter Card -->
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <!-- Filter Kelas -->
                        <div class="d-flex align-items-center">
                            <label class="fw-semibold text-muted mb-0 me-3">
                                <i class="fas fa-filter me-2" style="color: #8b5cf6;"></i>Filter Kelas:
                            </label>
                            <form method="GET" action="{{ route('anggotas.index') }}" class="d-inline">
                                <select name="kelas" onchange="this.form.submit()" class="form-control" style="max-width: 300px; border-radius: 50px; border: 1px solid #e0e0e0;">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach ($daftar_kelas as $k)
                                        <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </form>
                            @if(request('kelas'))
                                <a href="{{ route('anggotas.index') }}" class="btn btn-sm ms-3" style="background-color: #ff6b6b; color: white; border-radius: 50px; padding: 8px 20px;">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @endif
                        </div>

                        <!-- Tombol Hapus Semua (Tahun Ajaran Baru) - PAKAI CONFIRM JS -->
                        @if(count($anggotas) > 0)
                        <button type="button" 
                                class="btn btn-danger" 
                                style="border-radius: 50px; padding: 8px 25px; background: linear-gradient(135deg, #ff4757, #ff6b81); border: none;"
                                onclick="confirmDeleteAll({{ count($anggotas) }})">
                            <i class="fas fa-user-slash me-2"></i> Hapus Semua Anggota ({{ count($anggotas) }})
                        </button>
                        
                        <!-- Form untuk Hapus Semua (hidden) -->
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

    <!-- Tabel Anggota -->
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2d3436; font-weight: 600;">
                            <i class="fas fa-list me-2" style="color: #8b5cf6;"></i> Daftar Anggota
                            <span class="badge ms-2" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); color: #000;">{{ count($anggotas) }} anggota</span>
                        </h5>
                        
                        <!-- Search Box -->
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari anggota..." id="searchInput" style="border-radius: 50px 0 0 50px; border: 1px solid #e0e0e0;">
                            <button class="btn btn-sm" style="background: linear-gradient(45deg, #f7c0ec, #a7bdea); border-radius: 0 50px 50px 0; color: #000;" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="min-width: 1200px;" id="anggotaTable">
                            <thead style="background: linear-gradient(45deg, #f7c0ec, #a7bdea);">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
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
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('anggotas.edit', $item['anggota']->id) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #ffe066; color: #000; border: none; border-radius: 8px; padding: 6px 10px;"
                                               title="Edit anggota">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Tombol Lihat Peminjaman -->
                                            <a href="{{ route('anggotas.peminjaman', $item['anggota']->id) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #8fd19e; color: #000; border: none; border-radius: 8px; padding: 6px 10px;"
                                               title="Lihat riwayat peminjaman">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Tombol Hapus Individu - PAKAI CONFIRM JS -->
                                            <button type="button" 
                                                    class="btn btn-sm" 
                                                    style="background-color: #ff6b6b; color: white; border: none; border-radius: 8px; padding: 6px 10px;"
                                                    onclick="confirmDelete({{ $item['anggota']->id }}, '{{ $item['anggota']->nama }}', {{ $item['pinjamans']->where('status', 'belum dikembalikan')->count() }})"
                                                    title="Hapus anggota">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
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

<!-- Form untuk Hapus Individu (hidden) -->
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    /* Custom scroll untuk list buku */
    td::-webkit-scrollbar {
        width: 4px;
    }
    td::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    td::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #f7c0ec, #a7bdea);
        border-radius: 10px;
    }
    td::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ec4899, #8b5cf6);
    }
    
    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    // Fungsi untuk konfirmasi hapus individu
    function confirmDelete(id, nama, pinjamanAktif) {
        let message = `⚠️ HAPUS ANGGOTA\n\n`;
        message += `Nama: ${nama}\n`;
        message += `ID: ${id}\n\n`;
        
        if (pinjamanAktif > 0) {
            message += `PERHATIAN! Anggota ini memiliki ${pinjamanAktif} buku yang belum dikembalikan!\n`;
            message += `Stok buku akan dikembalikan otomatis.\n\n`;
        }
        
        message += `Menghapus anggota akan:\n`;
        message += `- Menghapus semua data peminjaman\n`;
        message += `- Mengembalikan stok buku\n`;
        message += `- Tidak dapat dikembalikan\n\n`;
        message += `Lanjutkan?`;
        
        if (confirm(message)) {
            // Set form action
            document.getElementById('deleteForm').action = `{{ url('anggotas') }}/${id}`;
            // Submit form
            document.getElementById('deleteForm').submit();
        }
    }

    // Fungsi untuk konfirmasi hapus semua
    function confirmDeleteAll(jumlah) {
        let message = `⚠️ HAPUS SEMUA ANGGOTA\n\n`;
        message += `Anda akan menghapus ${jumlah} anggota.\n\n`;
        message += `KONSEKUENSI:\n`;
        message += `- Semua data peminjaman akan dihapus\n`;
        message += `- Stok buku akan dikembalikan otomatis\n`;
        message += `- Data TIDAK DAPAT dikembalikan\n\n`;
        message += `KETIK 'HAPUS' untuk melanjutkan:`;
        
        let konfirmasi = prompt(message);
        
        if (konfirmasi === 'HAPUS') {
            document.getElementById('deleteAllForm').submit();
        } else if (konfirmasi !== null) {
            alert('Penghapusan dibatalkan - Kode salah');
        }
    }

    // Search functionality
    document.getElementById('searchButton').addEventListener('click', function() {
        filterTable();
    });

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            filterTable();
        }
    });

    function filterTable() {
        let searchValue = document.getElementById('searchInput').value.toLowerCase();
        let tableRows = document.querySelectorAll('#anggotaTable tbody tr');
        
        tableRows.forEach(function(row) {
            if (row.querySelector('td[colspan="7"]')) return; // Skip empty state row
            
            let text = row.textContent.toLowerCase();
            if (text.indexOf(searchValue) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush