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

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" required>
        </div>

        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="judul_buku" class="form-label">Judul Buku</label>
            <div class="input-group">
                <input type="text" name="judul_buku" id="judul_buku" class="form-control" value="{{ old('judul_buku') }}" required autocomplete="off" list="daftarBuku">
                <datalist id="daftarBuku">
                    @foreach($bookTitles ?? [] as $title)
                        <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </datalist>
                <button type="button" class="btn btn-outline-primary" id="cekBukuBtn">
                    <i class="fas fa-search"></i> Cek Buku
                </button>
            </div>
            <div id="statusBuku" class="mt-2"></div>
            <div id="saranBuku" class="mt-1"></div>
        </div>

        <div class="mb-3">
            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam') }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="belum dikembalikan" {{ old('status') == 'belum dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                <option value="sudah dikembalikan" {{ old('status') == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
        <a href="{{ route('pinjamans.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const judulBukuInput = document.getElementById('judul_buku');
        const cekBukuBtn = document.getElementById('cekBukuBtn');
        const statusBuku = document.getElementById('statusBuku');
        const saranBuku = document.getElementById('saranBuku');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('pinjamanForm');

        // Daftar buku yang tersedia dari server
        const availableBooks = @json($bookTitles ?? []);

        // Fungsi untuk mengecek apakah buku ada di database
        async function cekKetersediaanBuku(judul) {
            if (!judul.trim()) {
                statusBuku.innerHTML = '<span class="text-warning">⚠️ Masukkan judul buku terlebih dahulu</span>';
                saranBuku.innerHTML = '';
                submitBtn.disabled = true;
                return false;
            }

            statusBuku.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Sedang memeriksa buku...</span>';
            saranBuku.innerHTML = '';
            
            try {
                // Gunakan route check-book yang sudah dibuat
                const checkUrl = '/check-book?judul=' + encodeURIComponent(judul);
                
                console.log('Mengecek URL:', checkUrl); // Untuk debugging
                
                const response = await fetch(checkUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                
                const data = await response.json();
                
                if (data.exists) {
                    statusBuku.innerHTML = '<span class="text-success">✅ Buku ditemukan! <strong>' + (data.judul || judul) + '</strong> - Stok: ' + data.stok + '</span>';
                    saranBuku.innerHTML = '';
                    submitBtn.disabled = false;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Buku Ditemukan!',
                        text: 'Buku "' + judul + '" tersedia di perpustakaan. Stok: ' + data.stok,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return true;
                } else {
                    statusBuku.innerHTML = '<span class="text-danger">❌ ' + data.message + '</span>';
                    submitBtn.disabled = true;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Buku Tidak Ditemukan!',
                        text: data.message,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                    
                    // Tampilkan saran buku yang mirip
                    if (availableBooks.length > 0) {
                        let similarBooks = availableBooks.filter(book => 
                            book.toLowerCase().includes(judul.toLowerCase())
                        );
                        if (similarBooks.length > 0) {
                            saranBuku.innerHTML = '<span class="text-info">💡 Apakah yang Anda maksud: ' + similarBooks.slice(0, 3).join(', ') + '?</span>';
                        }
                    }
                    return false;
                }
            } catch (error) {
                console.error('Fetch error:', error);
                statusBuku.innerHTML = '<span class="text-danger">❌ Gagal memeriksa buku. Error: ' + error.message + '</span>';
                saranBuku.innerHTML = '';
                submitBtn.disabled = true;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memeriksa Buku',
                    text: 'Error: ' + error.message + '\n\nPastikan server berjalan di ' + window.location.origin,
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

        // Event klik tombol cek buku
        if (cekBukuBtn) {
            cekBukuBtn.addEventListener('click', async function() {
                const judul = judulBukuInput.value.trim();
                if (!judul) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Judul Buku Kosong',
                        text: 'Silakan masukkan judul buku terlebih dahulu!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                await cekKetersediaanBuku(judul);
            });
        }

        // Event blur (ketika input kehilangan fokus)
        if (judulBukuInput) {
            judulBukuInput.addEventListener('blur', async function() {
                const judul = this.value.trim();
                if (judul) {
                    await cekKetersediaanBuku(judul);
                }
            });
        }

        // Event submit form
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const judul = judulBukuInput.value.trim();
                
                if (!judul) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Judul Buku Kosong',
                        text: 'Silakan masukkan judul buku terlebih dahulu!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Memeriksa Buku...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const isValid = await cekKetersediaanBuku(judul);
                Swal.close();
                
                if (isValid) {
                    form.submit();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Buku Tidak Valid!',
                        text: 'Buku "' + judul + '" tidak ditemukan di database. Silakan pilih buku dari daftar yang tersedia.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
</script>

<style>
    #statusBuku {
        display: block;
        margin-top: 5px;
        font-size: 0.875rem;
    }
    #saranBuku {
        font-size: 0.875rem;
        margin-top: 5px;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    datalist {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endsection