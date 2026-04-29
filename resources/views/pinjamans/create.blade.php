@extends('layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold" style="color: #2d3436;">
                <i class="fas fa-plus-circle me-2" style="color: #6c5ce7;"></i>Catat Peminjaman Baru
            </h3>
            <p class="text-muted mb-0">Isi formulir di bawah ini untuk mencatat transaksi peminjaman buku.</p>
        </div>
        <a href="{{ route('pinjamans.index') }}" class="btn bg-white shadow-sm" style="border-radius: 50px; padding: 8px 20px; font-weight: 600; color: #636e72; border: 1px solid #dfe6e9;">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #d63031;">
            <strong><i class="fas fa-exclamation-circle me-2"></i> Ups! Ada kesalahan input:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #d63031;">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #00b894;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('pinjamans.store') }}" method="POST" id="pinjamanForm">
        @csrf

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe); padding: 10px 14px;">1</span> 
                    Informasi Peminjam & Jadwal
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="nama" class="form-label fw-semibold text-muted">Nama Peminjam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="nama" id="nama" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('nama') }}" placeholder="Ketik nama lengkap..." required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="kelas" class="form-label fw-semibold text-muted">Kelas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-graduation-cap text-muted"></i></span>
                            <input type="text" name="kelas" id="kelas" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('kelas') }}" placeholder="Contoh: 2A, 3B..." required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="jenis_kelamin" class="form-label fw-semibold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-venus-mars text-muted"></i></span>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select form-select-lg custom-input border-start-0 ps-0" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="tanggal_pinjam" class="form-label fw-semibold text-muted">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_kembali" class="form-label fw-semibold text-muted">Jatuh Tempo (Otomatis +7 Hari) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-clock text-muted"></i></span>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control form-control-lg custom-input border-start-0 ps-0 text-primary fw-bold" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label fw-semibold text-muted">Status Awal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-info-circle text-muted"></i></span>
                            <select name="status" id="status" class="form-select form-select-lg custom-input border-start-0 ps-0 bg-light" required>
                                <option value="belum dikembalikan" selected>Belum Dikembalikan (Proses Pinjam)</option>
                                <option value="sudah dikembalikan">Sudah Dikembalikan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #00b894, #55efc4); padding: 10px 14px;">2</span> 
                    Buku yang Dipinjam
                </h5>
                <button type="button" class="btn btn-sm shadow-sm" id="btnTambahBuku" style="border-radius: 50px; padding: 8px 20px; font-weight: 600; background: #00b894; color: white; border: none;">
                    <i class="fas fa-plus me-2"></i> Tambah Buku Lain
                </button>
            </div>
            
            <div class="card-body p-4">
                <datalist id="daftarBuku">
                    @foreach($bookTitles ?? [] as $title)
                        <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </datalist>

                <div id="buku-container">
                    <div class="book-row p-3 mb-3" style="background-color: #fdfdfd; border-radius: 15px; border: 1px solid #dfe6e9; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        <label class="form-label fw-bold" style="color: #636e72;">Judul Buku <span class="book-number" style="color: #00b894;">1</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-book-open"></i></span>
                            <input type="text" name="judul_buku[]" class="form-control border-start-0 ps-0 custom-input-group judul-buku-input" placeholder="Ketik atau pilih judul buku dari daftar..." required autocomplete="off" list="daftarBuku">
                            
                            <button type="button" class="btn px-4 btn-cek-buku fw-bold" style="border-radius: 0 10px 10px 0; background-color: #6c5ce7; color: white; border-color: #6c5ce7;">
                                <i class="fas fa-search me-1"></i> Cek Stok
                            </button>
                            
                            <button type="button" class="btn btn-danger px-3 btn-hapus-buku ms-2" style="display:none; border-radius: 10px;" title="Hapus baris ini">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="status-buku mt-2 fw-medium ms-1"></div>
                        <div class="saran-buku mt-1 ms-1"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow" id="submitBtn" style="border-radius: 50px; font-weight: 700; background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); border: none;">
                <i class="fas fa-save me-2"></i> Simpan Transaksi Peminjaman
            </button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body { background-color: #f4f6f9; }
    .custom-input { 
        border-radius: 12px; 
        border: 1px solid #dfe6e9; 
        font-size: 0.95rem; 
        background-color: #ffffff;
    }
    .custom-input:focus, .form-select:focus { 
        border-color: #6c5ce7; 
        box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); 
    }
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 1px solid #dfe6e9;
    }
    .custom-input-group:focus { border-color: #dfe6e9; box-shadow: none; }
    .input-group:focus-within .input-group-text, .input-group:focus-within .custom-input-group { 
        border-color: #6c5ce7; 
    }
    .book-row { animation: slideDown 0.3s ease-out; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .status-buku { font-size: 0.9rem; }
    .saran-buku { font-size: 0.85rem; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- FITUR 1: AUTO-FILL JATUH TEMPO (+7 HARI) ---
        const inputTglPinjam = document.getElementById('tanggal_pinjam');
        const inputTglKembali = document.getElementById('tanggal_kembali');

        if(inputTglPinjam && inputTglKembali) {
            inputTglPinjam.addEventListener('change', function() {
                let pinjamDate = new Date(this.value);
                if (!isNaN(pinjamDate.getTime())) {
                    pinjamDate.setDate(pinjamDate.getDate() + 7); // Tambah 7 hari
                    
                    let y = pinjamDate.getFullYear();
                    let m = String(pinjamDate.getMonth() + 1).padStart(2, '0');
                    let d = String(pinjamDate.getDate()).padStart(2, '0');
                    
                    inputTglKembali.value = `${y}-${m}-${d}`;
                }
            });
        }

        // --- FITUR 2: DINAMIS MULTIPLE BOOKS ---
        const container = document.getElementById('buku-container');
        const btnTambah = document.getElementById('btnTambahBuku');
        const availableBooks = @json($bookTitles ?? []);

        function updateBookNumbers() {
            const rows = container.querySelectorAll('.book-row');
            rows.forEach((row, index) => {
                row.querySelector('.book-number').textContent = index + 1;
                const btnHapus = row.querySelector('.btn-hapus-buku');
                if (rows.length > 1) {
                    btnHapus.style.display = 'block';
                } else {
                    btnHapus.style.display = 'none';
                }
            });
        }

        btnTambah.addEventListener('click', function() {
            const rowCount = container.querySelectorAll('.book-row').length + 1;
            const newRow = document.createElement('div');
            newRow.className = 'book-row p-3 mb-3';
            newRow.style = 'background-color: #fdfdfd; border-radius: 15px; border: 1px solid #dfe6e9; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);';
            newRow.innerHTML = `
                <label class="form-label fw-bold" style="color: #636e72;">Judul Buku <span class="book-number" style="color: #00b894;">${rowCount}</span></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-book-open"></i></span>
                    <input type="text" name="judul_buku[]" class="form-control border-start-0 ps-0 custom-input-group judul-buku-input" placeholder="Ketik atau pilih judul buku dari daftar..." required autocomplete="off" list="daftarBuku">
                    <button type="button" class="btn px-4 btn-cek-buku fw-bold" style="border-radius: 0 10px 10px 0; background-color: #6c5ce7; color: white; border-color: #6c5ce7;"><i class="fas fa-search me-1"></i> Cek Stok</button>
                    <button type="button" class="btn btn-danger px-3 btn-hapus-buku ms-2" style="border-radius: 10px;" title="Hapus baris ini"><i class="fas fa-trash"></i></button>
                </div>
                <div class="status-buku mt-2 fw-medium ms-1"></div>
                <div class="saran-buku mt-1 ms-1"></div>
            `;
            container.appendChild(newRow);
            updateBookNumbers();
        });

        container.addEventListener('click', async function(e) {
            if (e.target.closest('.btn-hapus-buku')) {
                e.target.closest('.book-row').remove();
                updateBookNumbers();
            }
            if (e.target.closest('.btn-cek-buku')) {
                const row = e.target.closest('.book-row');
                const input = row.querySelector('.judul-buku-input');
                const statusElement = row.querySelector('.status-buku');
                const saranElement = row.querySelector('.saran-buku');
                const judul = input.value.trim();

                if (!judul) {
                    Swal.fire({ icon: 'warning', title: 'Field Kosong', text: 'Silakan ketik judul buku terlebih dahulu!', confirmButtonColor: '#6c5ce7' });
                    return;
                }
                await cekKetersediaanBuku(judul, statusElement, saranElement);
            }
        });

        container.addEventListener('focusout', async function(e) {
            if (e.target.classList.contains('judul-buku-input')) {
                const row = e.target.closest('.book-row');
                const statusElement = row.querySelector('.status-buku');
                const saranElement = row.querySelector('.saran-buku');
                const judul = e.target.value.trim();
                if (judul) await cekKetersediaanBuku(judul, statusElement, saranElement);
            }
        });

        async function cekKetersediaanBuku(judul, statusEl, saranEl) {
            statusEl.innerHTML = '<span class="text-primary"><i class="fas fa-spinner fa-spin me-1"></i> Memeriksa database...</span>';
            saranEl.innerHTML = '';
            
            try {
                const checkUrl = '{{ route("check-book") }}?judul=' + encodeURIComponent(judul);
                const response = await fetch(checkUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('HTTP Error');
                const data = await response.json();
                
                if (data.exists) {
                    statusEl.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i> Tersedia! Stok: <strong>${data.stok}</strong> buku</span>`;
                    return true;
                } else {
                    statusEl.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i> ${data.message}</span>`;
                    if (availableBooks.length > 0) {
                        let similarBooks = availableBooks.filter(book => book.toLowerCase().includes(judul.toLowerCase()));
                        if (similarBooks.length > 0) {
                            saranEl.innerHTML = '<span class="text-muted"><i class="fas fa-lightbulb text-warning me-1"></i> Maksud Anda: <strong class="text-primary">' + similarBooks.slice(0, 3).join('</strong> atau <strong class="text-primary">') + '</strong>?</span>';
                        }
                    }
                    return false;
                }
            } catch (error) {
                statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Gagal memeriksa koneksi ke server.</span>';
                return false;
            }
        }
    });
</script>
@endsection