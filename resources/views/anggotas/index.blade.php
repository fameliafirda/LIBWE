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

    @if(session('warning'))
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #f39c12;">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
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

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="btnBulkDelete" class="btn btn-outline-danger d-none" style="border-radius: 50px; padding: 8px 25px; font-weight: 600; border-width: 2px;">
                                <i class="fas fa-check-square me-2"></i>Hapus Terpilih (<span id="checkCount">0</span>)
                            </button>

                            @if(count($anggotas) > 0)
                            <button type="button" 
                                    class="btn btn-danger" 
                                    style="border-radius: 50px; padding: 8px 25px; background: linear-gradient(135deg, #ff4757, #ff6b81); border: none; font-weight: 500;"
                                    onclick="confirmDeleteAll({{ count($anggotas) }})">
                                <i class="fas fa-user-slash me-2"></i>Hapus Semua Anggota ({{ count($anggotas) }})
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
    </div>

    <form id="formBulkDelete" action="{{ route('anggotas.bulkDelete') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_ids" id="selected_ids" value="">

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
                                        <th class="text-center" style="width: 40px; vertical-align: middle;">
                                            <input type="checkbox" id="checkAll" class="form-check-input shadow-sm" style="cursor: pointer; transform: scale(1.2); margin: 0 auto;">
                                        </th>
                                        <th class="text-center" style="width: 60px; vertical-align: middle;">No</th>
                                        <th style="width: 120px; vertical-align: middle;">NISN</th>
                                        <th style="width: 200px; vertical-align: middle;">Nama</th>
                                        <th style="width: 100px; vertical-align: middle;">Kelas</th>
                                        <th style="width: 150px; vertical-align: middle;">Jenis Kelamin</th>
                                        <th style="vertical-align: middle;">Buku yang Dipinjam</th>
                                        <th style="width: 150px; vertical-align: middle;" class="text-center">Total Denda</th>
                                        <th style="width: 180px; vertical-align: middle;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($anggotas as $item)
                                    <tr style="vertical-align: middle;" id="row-{{ $item->id }}">
                                        <td class="text-center">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="form-check-input anggota-checkbox shadow-sm" style="cursor: pointer; transform: scale(1.2); margin: 0 auto;">
                                        </td>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-secondary px-2 py-1" style="font-size: 0.9rem;">{{ $item->nisn ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user" style="color: #8b5cf6;"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $item->nama }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark px-3 py-2" style="font-size: 0.85rem;">{{ $item->kelas }}</span>
                                        </td>
                                        <td>
                                            @if($item->jenis_kelamin == 'Laki-laki')
                                                <span class="badge bg-info text-white px-3 py-2" style="font-size: 0.85rem;">
                                                    <i class="fas fa-mars me-1"></i> Laki-laki
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white px-3 py-2" style="font-size: 0.85rem;">
                                                    <i class="fas fa-venus me-1"></i> Perempuan
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->pinjamans->count() > 0)
                                                <div style="max-height: 100px; overflow-y: auto; padding-right: 5px;">
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($item->pinjamans as $peminjaman)
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
                                            @if($item->pinjamans->sum('denda') > 0)
                                                <span class="fw-bold text-danger">
                                                    Rp {{ number_format($item->pinjamans->sum('denda'), 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success px-3 py-2" style="font-size: 0.85rem;">Rp 0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center" style="gap: 25px;">
                                                <a href="{{ route('anggotas.edit', $item->id) }}" 
                                                   style="color: #f59e0b; font-size: 1.4rem; transition: 0.2s; text-decoration: none;"
                                                   onmouseover="this.style.transform='scale(1.2)'"
                                                   onmouseout="this.style.transform='scale(1)'"
                                                   title="Edit anggota">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('anggotas.peminjaman', $item->id) }}" 
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
                                                        onclick="confirmDeleteSingle({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->pinjamans->where('status', 'belum dikembalikan')->count() }})"
                                                        title="Hapus anggota">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
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
    </form>
</div>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.anggota-checkbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const checkCount = document.getElementById('checkCount');
        const formBulkDelete = document.getElementById('formBulkDelete');
        const inputSelectedIds = document.getElementById('selected_ids');

        function updateBulkDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.anggota-checkbox:checked');
            const count = checkedBoxes.length;
            checkCount.innerText = count;

            if (count > 0) {
                btnBulkDelete.classList.remove('d-none');
            } else {
                btnBulkDelete.classList.add('d-none');
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!this.checked) {
                    checkAll.checked = false;
                } else if (document.querySelectorAll('.anggota-checkbox:checked').length === checkboxes.length) {
                    checkAll.checked = true;
                }
                updateBulkDeleteButton();
            });
        });

        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.anggota-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                
                inputSelectedIds.value = JSON.stringify(selectedIds);

                Swal.fire({
                    title: 'Anda yakin?',
                    text: `Anda akan menghapus ${selectedIds.length} anggota sekaligus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Terpilih!',
                    cancelButtonText: 'Tidak, Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formBulkDelete.submit();
                    }
                });
            });
        }

        document.getElementById('searchButton').addEventListener('click', filterTable);
        document.getElementById('searchInput').addEventListener('keyup', (e) => { 
            if(e.key === 'Enter') filterTable(); 
        });

        // Diperbaiki agar live-search lokal mengabaikan data kolom checkbox yang tersembunyi
        function filterTable() {
            let searchValue = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#anggotaTable tbody tr').forEach(row => {
                if (row.querySelector('td[colspan="9"]')) return;
                row.style.display = row.textContent.toLowerCase().includes(searchValue) ? '' : 'none';
            });
        }
    });

    function confirmDeleteSingle(id, nama, pinjamanAktif) {
        let textWarning = `Data anggota atas nama ${nama} akan dihapus permanen.`;
        if (pinjamanAktif > 0) {
            textWarning = `PERHATIAN! Anggota ini masih meminjam ${pinjamanAktif} buku yang belum dikembalikan. Yakin ingin menghapus?`;
        }

        Swal.fire({
            title: 'Hapus Anggota Ini?',
            text: textWarning,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').action = `{{ url('anggotas') }}/${id}`;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    function confirmDeleteAll(jumlah) {
        Swal.fire({
            title: `Hapus SEMUA (${jumlah}) Anggota?`,
            text: "Ketik 'HAPUS' di bawah ini untuk melanjutkan:",
            input: 'text',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Eksekusi',
            cancelButtonText: 'Batal',
            preConfirm: (inputValue) => {
                if (inputValue !== 'HAPUS') {
                    Swal.showValidationMessage('Ketik HAPUS dengan huruf kapital untuk konfirmasi!');
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAllForm').submit();
            }
        });
    }
</script>
@endpush