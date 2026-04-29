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
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Nama Peminjam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="nama" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('nama', $pinjaman->nama) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Kelas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-graduation-cap text-muted"></i></span>
                            <input type="text" name="kelas" class="form-control form-control-lg custom-input border-start-0 ps-0" value="{{ old('kelas', $pinjaman->kelas) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-venus-mars text-muted"></i></span>
                            <select name="jenis_kelamin" class="form-select form-select-lg custom-input border-start-0 ps-0" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $pinjaman->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $pinjaman->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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