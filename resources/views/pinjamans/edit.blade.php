@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold" style="color: #2d3436;">
                <i class="fas fa-edit me-2" style="color: #fdcb6e;"></i>Edit Data Peminjaman
            </h3>
            <p class="text-muted mb-0">Perbarui informasi transaksi peminjaman buku yang sudah ada.</p>
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

    <form action="{{ route('pinjamans.update', $pinjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe); padding: 10px 14px;">1</span> 
                    Informasi Peminjam
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    
                    <div class="col-12 mb-2">
                        <label class="form-label fw-semibold text-muted">NISN Siswa Peminjam <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                            <input type="text" name="nisn" id="nisnInput" class="form-control border-start-0 ps-0 custom-input-group" placeholder="Masukkan NISN siswa lalu klik Cek..." value="{{ old('nisn', $pinjaman->anggota->nisn ?? '') }}" required>
                            <button class="btn px-4 fw-bold" type="button" id="btnCekNisn" style="border-radius: 0 10px 10px 0; background: linear-gradient(45deg, #6c5ce7, #a29bfe); color: white; border: none;">
                                <i class="fas fa-search me-1"></i> Cek Siswa
                            </button>
                        </div>
                        <small class="text-muted ms-1 mt-1 d-block" id="nisnStatusMsg">Jika terjadi perubahan peminjam, ketik NISN baru lalu klik Cek Siswa.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Nama Peminjam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="nama" id="nama" class="form-control form-control-lg custom-input border-start-0 ps-0 bg-light" value="{{ old('nama', $pinjaman->nama) }}" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Kelas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-graduation-cap text-muted"></i></span>
                            <input type="text" name="kelas" id="kelas" class="form-control form-control-lg custom-input border-start-0 ps-0 bg-light" value="{{ old('kelas', $pinjaman->kelas) }}" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-venus-mars text-muted"></i></span>
                            <input type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control form-control-lg custom-input border-start-0 ps-0 bg-light" value="{{ old('jenis_kelamin', $pinjaman->jenis_kelamin) }}" readonly required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #00b894, #55efc4); padding: 10px 14px;">2</span> 
                    Detail Buku & Jadwal
                </h5>
            </div>
            
            <div class="card-body p-4">
                <datalist id="daftarBuku">
                    @if(isset($books))
                        @foreach($books as $buku)
                            <option value="{{ $buku->judul }}">{{ $buku->judul }}</option>
                        @endforeach
                    @endif
                </datalist>

                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-muted">Judul Buku <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-book-open text-muted"></i></span>
                            <input type="text" name="judul_buku" class="form-control custom-input border-start-0 ps-0" value="{{ old('judul_buku', $pinjaman->judul_buku) }}" required autocomplete="off" list="daftarBuku">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tanggal_pinjam" id="edit_tanggal_pinjam" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Jatuh Tempo / Tanggal Kembali</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-clock text-muted"></i></span>
                            <input type="date" name="tanggal_kembali" id="edit_tanggal_kembali" class="form-control form-control-lg custom-input border-start-0 ps-0 fw-bold" value="{{ old('tanggal_kembali', optional($pinjaman->tanggal_kembali)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Status Peminjaman <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-info-circle text-muted"></i></span>
                            <select name="status" class="form-select form-select-lg custom-input border-start-0 ps-0 bg-light" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="belum dikembalikan" {{ old('status', $pinjaman->status) == 'belum dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan (Sedang Dipinjam)</option>
                                <option value="sudah dikembalikan" {{ old('status', $pinjaman->status) == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan (Selesai)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-lg px-5 shadow" style="border-radius: 50px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); border: none;">
                <i class="fas fa-save me-2"></i> Update Transaksi
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
        border-color: #fdcb6e; 
        box-shadow: 0 0 0 4px rgba(253, 203, 110, 0.15); 
    }
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 1px solid #dfe6e9;
    }
    .input-group:focus-within .input-group-text { 
        border-color: #fdcb6e; 
    }
    .form-control.border-start-0, .form-select.border-start-0 {
        border-radius: 0 12px 12px 0;
    }
    /* Mencegah kursor text saat read-only */
    input[readonly] { cursor: not-allowed; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- FITUR AUTO-FILL NISN SISWA (SAMA DENGAN FORM CREATE) ---
        const btnCekNisn = document.getElementById('btnCekNisn');
        const inputNisn = document.getElementById('nisnInput');
        const inputNama = document.getElementById('nama');
        const inputKelas = document.getElementById('kelas');
        const inputJk = document.getElementById('jenis_kelamin');
        const nisnMsg = document.getElementById('nisnStatusMsg');

        function cekDataSiswa() {
            let nisnVal = inputNisn.value.trim();
            if (!nisnVal) {
                Swal.fire({ icon: 'warning', title: 'Data Kosong', text: 'Silakan isi NISN terlebih dahulu!', confirmButtonColor: '#6c5ce7' });
                return;
            }

            btnCekNisn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mencari...';
            btnCekNisn.disabled = true;
            
            // Panggil API get-anggota
            fetch(`{{ url('/pinjamans/get-anggota') }}/${nisnVal}`)
                .then(response => response.json())
                .then(data => {
                    btnCekNisn.innerHTML = '<i class="fas fa-search me-1"></i> Cek Siswa';
                    btnCekNisn.disabled = false;

                    if (data.success) {
                        inputNama.value = data.data.nama;
                        inputKelas.value = data.data.kelas;
                        inputJk.value = data.data.jenis_kelamin;
                        
                        inputNisn.classList.add('is-valid');
                        inputNisn.classList.remove('is-invalid');
                        nisnMsg.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Siswa ditemukan! Data otomatis diperbarui.</span>';
                    } else {
                        inputNama.value = '';
                        inputKelas.value = '';
                        inputJk.value = '';
                        
                        inputNisn.classList.add('is-invalid');
                        inputNisn.classList.remove('is-valid');
                        nisnMsg.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle"></i> ${data.message}</span>`;
                        Swal.fire({ icon: 'error', title: 'Tidak Ditemukan', text: data.message, confirmButtonColor: '#d63031' });
                    }
                })
                .catch(error => {
                    btnCekNisn.innerHTML = '<i class="fas fa-search me-1"></i> Cek Siswa';
                    btnCekNisn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Error Koneksi', text: 'Gagal terhubung ke sistem. Coba lagi.', confirmButtonColor: '#d63031' });
                });
        }

        btnCekNisn.addEventListener('click', cekDataSiswa);
        inputNisn.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cekDataSiswa();
            }
        });


        // --- FITUR AUTO-FILL JATUH TEMPO (+7 HARI) SAAT MENGUBAH TANGGAL PINJAM ---
        const inputTglPinjam = document.getElementById('edit_tanggal_pinjam');
        const inputTglKembali = document.getElementById('edit_tanggal_kembali');

        if(inputTglPinjam && inputTglKembali) {
            inputTglPinjam.addEventListener('change', function() {
                let pinjamDate = new Date(this.value);
                if (!isNaN(pinjamDate.getTime())) {
                    pinjamDate.setDate(pinjamDate.getDate() + 7); // Tambah 7 hari
                    
                    let y = pinjamDate.getFullYear();
                    let m = String(pinjamDate.getMonth() + 1).padStart(2, '0');
                    let d = String(pinjamDate.getDate()).padStart(2, '0');
                    
                    inputTglKembali.value = `${y}-${m}-${d}`;
                    // Beri efek highlight sesaat untuk menandakan nilai berubah otomatis
                    inputTglKembali.style.transition = "background-color 0.3s";
                    inputTglKembali.style.backgroundColor = "#fff3cd";
                    setTimeout(() => {
                        inputTglKembali.style.backgroundColor = "#ffffff";
                    }, 800);
                }
            });
        }
    });
</script>
@endsection