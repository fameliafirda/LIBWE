@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold" style="color: #2d3436;">
                <i class="fas fa-edit me-2" style="color: #fdcb6e;"></i>Edit Data Pengembalian
            </h3>
            <p class="text-muted mb-0">Perbarui informasi tanggal pengembalian buku.</p>
        </div>
        <a href="{{ route('pengembalians.index') }}" class="btn bg-white shadow-sm" style="border-radius: 50px; padding: 8px 20px; font-weight: 600; color: #636e72; border: 1px solid #dfe6e9;">
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

    <form action="{{ route('pengembalians.update', $pengembalian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #fdcb6e, #e17055); padding: 10px 14px;">
                        <i class="fas fa-undo-alt"></i>
                    </span> 
                    Form Edit Pengembalian
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Nama Peminjam</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control form-control-lg custom-input border-start-0 ps-0 bg-light" value="{{ $pengembalian->nama ?? '-' }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Judul Buku</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-book text-muted"></i></span>
                            <input type="text" class="form-control form-control-lg custom-input border-start-0 ps-0 bg-light" value="{{ $pengembalian->judul_buku ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="tanggal_pengembalian" class="form-label fw-semibold text-muted">Tanggal Dikembalikan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-check text-muted"></i></span>
                            <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="form-control form-control-lg custom-input border-start-0 ps-0 text-success fw-bold" value="{{ old('tanggal_pengembalian', $pengembalian->tanggal_pengembalian) }}" required>
                        </div>
                        <small class="text-muted ms-1 mt-1 d-block">
                            <i class="fas fa-info-circle me-1 text-warning"></i> Mengubah tanggal pengembalian akan menyesuaikan ulang perhitungan denda secara otomatis.
                        </small>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-lg px-5 shadow" style="border-radius: 50px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); border: none;">
                <i class="fas fa-save me-2"></i> Update Pengembalian
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
    .custom-input:focus { 
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
    .form-control.border-start-0 {
        border-radius: 0 12px 12px 0;
    }
    input[readonly] { 
        cursor: not-allowed; 
    }
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
</style>
@endsection