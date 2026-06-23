@extends('layouts.app')

@section('title', 'DB Master Hari Libur')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #8ecae6 0%, #764ba2 100%); border-radius: 0 20px 20px 0;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-7">
                            <h3 class="text-white mb-2" style="font-weight: 600;">
                                <i class="fas fa-calendar-alt me-2"></i> DB Master Hari Libur Nasional
                            </h3>
                            <p class="text-white opacity-75 mb-0">Pangkalan data kalender libur & cuti bersama Indonesia untuk basis akurasi denda</p>
                        </div>
                        <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                            <button type="button" class="btn btn-warning me-2 text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalGenerateTahun" style="border-radius: 50px; padding: 10px 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <i class="fas fa-sync-alt me-2"></i> Sinkronisasi Per Tahun
                            </button>
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalTambahManual" style="border-radius: 50px; padding: 10px 20px; font-weight: 500; color: #764ba2; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <i class="fas fa-plus-circle me-1"></i> Tambah Manual
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 15px; border-left: 5px solid #28a745;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #dc3545;">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <label class="fw-bold text-muted mb-0 me-3"><i class="fas fa-filter me-2" style="color: #8ecae6;"></i>Pilih Tahun Master:</label>
                        <form method="GET" action="{{ route('hari-liburs.index') }}" class="d-inline">
                            <select name="tahun" onchange="this.form.submit()" class="form-control form-select-sm" style="max-width: 150px; border-radius: 50px; border: 1px solid #e0e0e0; font-weight: 700;">
                                @foreach($daftarTahun as $t)
                                    <option value="{{ $t }}" {{ $tahunDipilih == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2 fw-bold" style="border-radius: 20px; font-size: 0.9rem;">
                        Terdata: {{ $hariLiburs->count() }} Hari Libur di Tahun {{ $tahunDipilih }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-nowrap align-middle">
                            <thead style="background: linear-gradient(45deg, #ffcfd2, #8ecae6);">
                                <tr>
                                    <th class="text-center" style="width: 70px;">No</th>
                                    <th style="width: 180px;">Tanggal Libur</th>
                                    <th>Keterangan Hari Libur Nasional</th>
                                    <th style="width: 180px;" class="text-center">Jenis Libur</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hariLiburs as $index => $libur)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-white text-dark border border-secondary px-3 py-2 fw-bold" style="font-size: 0.85rem; border-radius: 10px;">
                                            <i class="fas fa-calendar-day me-2 text-primary"></i>{{ \Carbon\Carbon::parse($libur->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold" style="color: #2b2d42;">{{ $libur->keterangan }}</td>
                                    <td class="text-center">
                                        @if($libur->jenis == 'nasional')
                                            <span class="badge bg-danger px-3 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 700;">Libur Nasional</span>
                                        @else
                                            <span class="badge bg-info text-dark px-3 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 700;">Cuti Bersama</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 20px;">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditLibur{{ $libur->id }}" style="color: #f59e0b; font-size: 1.2rem; text-decoration: none;" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hari-liburs.destroy', $libur->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus tanggal merah ini dari database master?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: transparent; border: none; color: #ef4444; font-size: 1.2rem; padding: 0; cursor: pointer;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEditLibur{{ $libur->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 20px; border: none;">
                                            <div class="modal-header border-0 bg-light py-3 px-4" style="border-radius: 20px 20px 0 0;">
                                                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i>Ubah Data Master</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('hari-liburs.update', $libur->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">Tanggal</label>
                                                        <input type="date" name="tanggal" class="form-control" style="border-radius: 10px;" value="{{ $libur->tanggal }}" readonly required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">Keterangan Hari Libur</label>
                                                        <input type="text" name="keterangan" class="form-control" style="border-radius: 10px;" value="{{ $libur->keterangan }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">Jenis Klasifikasi</label>
                                                        <select name="jenis" class="form-select" style="border-radius: 10px;" required>
                                                            <option value="nasional" {{ $libur->jenis == 'nasional' ? 'selected' : '' }}>Libur Nasional</option>
                                                            <option value="cuti_bersama" {{ $libur->jenis == 'cuti_bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 bg-light py-3 px-4" style="border-radius: 0 0 20px 20px;">
                                                    <button type="button" class="btn btn-secondary px-4" style="border-radius: 50px;" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning px-4 text-white fw-bold" style="border-radius: 50px;">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-times fa-4x mb-3" style="color: #e0e0e0;"></i>
                                        <h6>Belum ada database master untuk Tahun {{ $tahunDipilih }}</h6>
                                        <p class="small text-muted mb-3">Silakan klik tombol "Sinkronisasi Per Tahun" untuk mengisi data otomatis lewat internet</p>
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

<div class="modal fade" id="modalTambahManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 bg-light py-3 px-4" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Manual Kalender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hari-liburs.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" style="border-radius: 10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Keterangan Libur</label>
                        <input type="text" name="keterangan" class="form-control" style="border-radius: 10px;" placeholder="Contoh: Libur Kelulusan Sekolah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jenis Libur</label>
                        <select name="jenis" class="form-select" style="border-radius: 10px;" required>
                            <option value="nasional">Libur Nasional</option>
                            <option value="cuti_bersama">Cuti Bersama</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3 px-4" style="border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-secondary px-4" style="border-radius: 50px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 50px; background: linear-gradient(135deg, #8ecae6 0%, #764ba2 100%); border: none;">Simpan Ke DB</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGenerateTahun" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 bg-light py-3 px-4" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-sync-alt me-2 text-warning"></i>Sinkronisasi Kalender Tahunan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hari-liburs.generate') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-3">Masukkan angka tahun yang ingin ditarik datanya dari API pusat SKB 3 Menteri masuk ke Database Master Perpustakaan:</p>
                    <div class="mx-auto" style="max-width: 200px;">
                        <input type="number" name="tahun_generate" class="form-control text-center fw-bold fs-4" style="border-radius: 15px; border: 2px solid #764ba2;" value="{{ $tahunDipilih }}" min="2020" max="2050" required>
                    </div>
                    <small class="text-danger d-block mt-2"><i class="fas fa-info-circle me-1"></i>Aksi ini otomatis melewati penguncian SSL lokal Laragon.</small>
                </div>
                <div class="modal-footer border-0 bg-light py-3 px-4" style="border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-secondary px-4" style="border-radius: 50px;" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold text-dark" style="border-radius: 50px;">Mulai Ambil Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection