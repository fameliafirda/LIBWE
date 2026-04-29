@extends('layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Tambah Data Peminjaman</h3>

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

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pinjamans.store') }}" method="POST" id="pinjamanForm">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white" style="border-radius: 15px 15px 0 0; padding: 20px;">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user text-primary me-2"></i> Data Peminjam</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Peminjam</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama..." required>
                        </div>

                        <div class="mb-3">
                            <label for="kelas" class="form-label fw-semibold">Kelas</label>
                            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="Contoh: 1A, 2B, 3C..." required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label fw-semibold">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label fw-semibold">Tanggal Kembali (Estimasi/Aktual)</label>
                            <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status Peminjaman</label>
                            <select name="status" class="form-select" required>
                                <option value="belum dikembalikan" selected>Belum Dikembalikan (Sedang Dipinjam)</option>
                                <option value="sudah dikembalikan">Sudah Dikembalikan (Selesai)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 20px;">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-book text-success me-2"></i> Buku yang Dipinjam</h5>
                        <button type="button" class="btn btn-sm btn-success" id="btnTambahBuku" style="border-radius: 50px; padding: 5px 15px;">
                            <i class="fas fa-plus"></i> Tambah Buku
                        </button>
                    </div>
                    <div class="card-body p-4">
                        
                        <datalist id="daftarBuku">
                            @foreach($bookTitles ?? [] as $title)
                                <option value="{{ $title }}">{{ $title }}</option>
                            @endforeach
                        </datalist>

                        <div id="buku-container">
                            <div class="book-row mb-3 pb-3 border-bottom">
                                <label class="form-label fw-bold">Judul Buku <span class="book-number text-primary">1</span></label>
                                <div class="input-group">
                                    <input type="text" name="judul_buku[]" class="form-control judul-buku-input" placeholder="Ketik atau pilih judul buku..." required autocomplete="off" list="daftarBuku">
                                    <button type="button" class="btn btn-outline-primary btn-cek-buku">
                                        <i class="fas fa-search"></i> Cek
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-hapus-buku" style="display:none;" title="Hapus baris ini">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="status-buku mt-2 fw-medium"></div>
                                <div class="saran-buku mt-1"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" style="border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-save me-2"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('pinjamans.index') }}" class="btn btn-light btn-lg" style="border-radius: 12px; font-weight: 600; color: #6c757d;">
                        <i class="fas fa-arrow-left me-2"></i> Batal / Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('buku-container');
        const btnTambah = document.getElementById('btnTambahBuku');
        const availableBooks = @json($bookTitles ?? []);

        // Fungsi Update Nomor Urut Buku
        function updateBookNumbers() {
            const rows = container.querySelectorAll('.book-row');
            rows.forEach((row, index) => {
                row.querySelector('.book-number').textContent = index + 1;
                // Tombol hapus hanya muncul jika form buku lebih dari 1
                const btnHapus = row.querySelector('.btn-hapus-buku');
                if (rows.length > 1) {
                    btnHapus.style.display = 'block';
                } else {
                    btnHapus.style.display = 'none';
                }
            });
        }

        // Fungsi Tambah Kolom Buku Baru
        btnTambah.addEventListener('click', function() {
            const rowCount = container.querySelectorAll('.book-row').length + 1;
            
            const newRow = document.createElement('div');
            newRow.className = 'book-row mb-3 pb-3 border-bottom';
            newRow.innerHTML = `
                <label class="form-label fw-bold">Judul Buku <span class="book-number text-primary">${rowCount}</span></label>
                <div class="input-group">
                    <input type="text" name="judul_buku[]" class="form-control judul-buku-input" placeholder="Ketik atau pilih judul buku..." required autocomplete="off" list="daftarBuku">
                    <button type="button" class="btn btn-outline-primary btn-cek-buku">
                        <i class="fas fa-search"></i> Cek
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-hapus-buku" title="Hapus baris ini">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="status-buku mt-2 fw-medium"></div>
                <div class="saran-buku mt-1"></div>
            `;
            container.appendChild(newRow);
            updateBookNumbers();
        });

        // Event Listener untuk Tombol Hapus & Cek (Menggunakan Event Delegation)
        container.addEventListener('click', async function(e) {
            // Jika tombol hapus ditekan
            if (e.target.closest('.btn-hapus-buku')) {
                const row = e.target.closest('.book-row');
                row.remove();
                updateBookNumbers();
            }
            
            // Jika tombol cek ditekan
            if (e.target.closest('.btn-cek-buku')) {
                const row = e.target.closest('.book-row');
                const input = row.querySelector('.judul-buku-input');
                const statusElement = row.querySelector('.status-buku');
                const saranElement = row.querySelector('.saran-buku');
                const judul = input.value.trim();

                if (!judul) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Kosong', 
                        text: 'Silakan masukkan judul buku terlebih dahulu!',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                await cekKetersediaanBuku(judul, statusElement, saranElement);
            }
        });

        // Event Blur untuk input otomatis cek ketika selesai ngetik
        container.addEventListener('focusout', async function(e) {
            if (e.target.classList.contains('judul-buku-input')) {
                const row = e.target.closest('.book-row');
                const statusElement = row.querySelector('.status-buku');
                const saranElement = row.querySelector('.saran-buku');
                const judul = e.target.value.trim();

                if (judul) {
                    await cekKetersediaanBuku(judul, statusElement, saranElement);
                }
            }
        });

        // Fungsi Cek Ketersediaan Buku ke Server
        async function cekKetersediaanBuku(judul, statusEl, saranEl) {
            statusEl.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Memeriksa ketersediaan buku...</span>';
            saranEl.innerHTML = '';
            
            try {
                // Menggunakan route('check-book') bawaan laravel untuk URL absolut
                const checkUrl = '{{ route("check-book") }}?judul=' + encodeURIComponent(judul);
                const response = await fetch(checkUrl, {
                    headers: { 
                        'Accept': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest' 
                    }
                });
                
                if (!response.ok) throw new Error('HTTP Error');
                const data = await response.json();
                
                if (data.exists) {
                    statusEl.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> Tersedia! Stok tersisa: ${data.stok} buku</span>`;
                    return true;
                } else {
                    statusEl.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle"></i> ${data.message}</span>`;
                    
                    // Saran buku jika salah ketik
                    if (availableBooks.length > 0) {
                        let similarBooks = availableBooks.filter(book => book.toLowerCase().includes(judul.toLowerCase()));
                        if (similarBooks.length > 0) {
                            saranEl.innerHTML = '<span class="text-primary small"><i class="fas fa-lightbulb"></i> Maksud Anda: <strong>' + similarBooks.slice(0, 3).join('</strong> atau <strong>') + '</strong>?</span>';
                        }
                    }
                    return false;
                }
            } catch (error) {
                statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memeriksa koneksi.</span>';
                return false;
            }
        }
    });
</script>

<style>
    body {
        background-color: #f8f9fc;
    }
    .status-buku { 
        font-size: 0.85rem; 
    }
    .saran-buku { 
        font-size: 0.8rem; 
    }
    .book-row { 
        animation: fadeIn 0.3s ease-in-out; 
    }
    .form-control:focus, .form-select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
    }
    .btn-outline-primary {
        color: #8b5cf6;
        border-color: #8b5cf6;
    }
    .btn-outline-primary:hover {
        background-color: #8b5cf6;
        border-color: #8b5cf6;
        color: white;
    }
    .text-primary {
        color: #8b5cf6 !important;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection