@extends('layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
@php
    $daftarTanggalMerah = \App\Models\HariLibur::pluck('tanggal')
        ->map(function($t) {
            return \Carbon\Carbon::parse($t)->toDateString();
        })->toArray();
@endphp

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold" style="color: #2d3436;">
                <i class="fas fa-undo-alt me-2" style="color: #00b894;"></i>Catat Pengembalian Buku
            </h3>
            <p class="text-muted mb-0">Pilih data peminjaman yang akan dikembalikan ke perpustakaan.</p>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border-left: 5px solid #d63031;">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('pengembalians.store') }}" method="POST">
        @csrf
        
        <input type="hidden" name="keterlambatan_input" id="keterlambatan_input" value="0">

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0" style="color: #2d3436;">
                    <span class="badge rounded-circle me-2" style="background: linear-gradient(135deg, #00b894, #55efc4); padding: 10px 14px;"><i class="fas fa-book"></i></span> 
                    Form Pengembalian
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-8">
                        <label for="pinjaman_id" class="form-label fw-semibold text-muted">Pilih Transaksi Peminjaman <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-list-ul text-muted"></i></span>
                            <select name="pinjaman_id" id="pinjaman_id" class="form-select form-select-lg custom-input border-start-0 ps-0" required>
                                <option value="">-- Cari & Pilih Data Peminjaman --</option>
                                @foreach($pinjamans as $pinjaman)
                                    @php
                                        // KLONING LOGIKA HITUNGAN DARI PEMINJAMAN BLADE SECARA LIVE
                                        $tglPinjam = \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
                                        $jatuhTempo = $tglPinjam->copy()->addDays(7);
                                        $terlambat = 0;
                                        $hariIni = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();

                                        if($hariIni->gt($jatuhTempo)) {
                                            $daftarTanggalMerah = [
                                                '2026-01-01', '2026-01-23', '2026-01-24', '2026-02-15', 
                                                '2026-03-19', '2026-03-20', '2026-03-21', '2026-04-03', 
                                                '2026-04-05', '2026-05-01', '2026-05-14', '2026-05-15', 
                                                '2026-05-24', '2026-05-25', '2026-06-01', '2026-11-27', 
                                                '2026-12-25',
                                            ];

                                            $currentDate = $jatuhTempo->copy()->addDay();
                                            while ($currentDate->lte($hariIni)) {
                                                $isMinggu = $currentDate->isSunday(); 
                                                $isTanggalMerah = in_array($currentDate->toDateString(), $daftarTanggalMerah);

                                                if (!$isMinggu && !$isTanggalMerah) {
                                                    $terlambat++;
                                                }
                                                $currentDate->addDay();
                                            }
                                        }
                                        $denda = $terlambat * 500;
                                    @endphp
                                    <option value="{{ $pinjaman->id }}" data-terlambat="{{ $terlambat }}" data-denda="{{ $denda }}">
                                        {{ $pinjaman->nama }} - {{ $pinjaman->buku->judul ?? $pinjaman->judul_buku ?? '-' }} (Jatuh Tempo: {{ $jatuhTempo->format('d/m/Y') }}) 
                                        @if($terlambat > 0)
                                            -- [ TERLAMBAT {{ $terlambat }} HARI ]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="info-box" class="alert mt-3" style="display: none; border-radius: 12px; border-left: 5px solid;">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="tanggal_pengembalian" class="form-label fw-semibold text-muted">Tanggal Dikembalikan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-check text-muted"></i></span>
                            <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="form-control form-control-lg custom-input border-start-0 ps-0 text-success fw-bold" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-lg px-5 shadow" style="border-radius: 50px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); border: none;">
                <i class="fas fa-check-circle me-2"></i> Proses Pengembalian
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
        border-color: #00b894; 
        box-shadow: 0 0 0 4px rgba(0, 184, 148, 0.15); 
    }
    .input-group-text {
        border-radius: 12px 0 0 12px;
        border: 1px solid #dfe6e9;
    }
    .input-group:focus-within .input-group-text { 
        border-color: #00b894; 
    }
    .form-control.border-start-0, .form-select.border-start-0 {
        border-radius: 0 12px 12px 0;
    }
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
</style>

@push('scripts')
<script>
    // JS Sakti untuk mengirim angka kembar ke controller
    document.getElementById('pinjaman_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var terlambat = selectedOption.getAttribute('data-terlambat');
        var denda = selectedOption.getAttribute('data-denda');
        var infoBox = document.getElementById('info-box');
        
        if (this.value !== "") {
            // Tembak nilainya ke input tersembunyi
            document.getElementById('keterlambatan_input').value = terlambat;
            
            // Munculkan notifikasi ke admin perpustakaan
            if(parseInt(terlambat) > 0) {
                infoBox.className = 'alert alert-danger mt-3 shadow-sm';
                infoBox.style.borderLeftColor = '#d63031';
                infoBox.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i><strong>Peringatan Denda!</strong><br>Siswa ini terlambat <strong>' + terlambat + ' hari</strong>. Tagihan Denda: <strong>Rp ' + parseInt(denda).toLocaleString('id-ID') + '</strong>';
                infoBox.style.display = 'block';
            } else {
                infoBox.className = 'alert alert-success mt-3 shadow-sm';
                infoBox.style.borderLeftColor = '#00b894';
                infoBox.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Aman!</strong><br>Pengembalian tepat waktu. Tidak ada denda.';
                infoBox.style.display = 'block';
            }
        } else {
            infoBox.style.display = 'none';
            document.getElementById('keterlambatan_input').value = 0;
        }
    });
</script>
@endpush
@endsection