<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjaman;
use App\Models\Book; // TAMBAHKAN MODEL BOOK
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    // Tampilkan semua data pengembalian
    public function index()
    {
        // Ambil semua pengembalian dengan relasi pinjaman
        $pengembalians = Pengembalian::with('pinjaman')->latest()->get();

        return view('pengembalians.index', compact('pengembalians'));
    }

    // Tampilkan form tambah pengembalian
    public function create()
    {
        $pinjamans = Pinjaman::where('status', 'belum dikembalikan')->get();
        return view('pengembalians.create', compact('pinjamans'));
    }

    // Simpan data pengembalian
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_pengembalian' => 'required|date'
        ]);

        $pinjaman = Pinjaman::findOrFail($validated['pinjaman_id']);

        // Cek jika sudah pernah dikembalikan
        if (Pengembalian::where('pinjaman_id', $pinjaman->id)->exists()) {
            return redirect()->back()->with('error', 'Pengembalian untuk peminjaman ini sudah dicatat.');
        }

        // PERBAIKAN: Set zona waktu ke Asia/Jakarta dan reset ke jam 00:00 (startOfDay)
        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
        $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7);
        $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->timezone('Asia/Jakarta')->startOfDay();

        // Bandingkan murni harinya saja
        $lamaTerlambat = 0;
        if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
            $lamaTerlambat = $tanggalHarusKembali->diffInDays($tanggalPengembalian);
        }

        $denda = $lamaTerlambat * 500;

        Pengembalian::create([
            'pinjaman_id' => $pinjaman->id,
            'nama' => $pinjaman->nama,
            'kelas' => $pinjaman->kelas,
            'judul_buku' => $pinjaman->judul_buku,
            'tanggal_kembali' => $tanggalHarusKembali->toDateString(), 
            'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
            'keterlambatan' => $lamaTerlambat,
            'denda' => $denda
        ]);

        // Update status pinjaman (tambahkan update denda dan tanggal kembali agar sinkron di semua halaman)
        $pinjaman->update([
            'status' => 'sudah dikembalikan', 
            'denda' => $denda, 
            'tanggal_kembali' => $tanggalPengembalian->toDateString()
        ]);
        
        // TAMBAH STOK BUKU
        $buku = Book::where('judul', $pinjaman->judul_buku)->first();
        if ($buku) {
            $buku->increment('stok'); // Gunakan fungsi bawaan Laravel agar lebih stabil
        }

        return redirect()->route('pengembalians.index')->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function edit(Pengembalian $pengembalian)
    {
        return view('pengembalians.edit', compact('pengembalian'));
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $validated = $request->validate([
            'tanggal_pengembalian' => 'required|date'
        ]);

        $pinjaman = $pengembalian->pinjaman;
        
        // PERBAIKAN: Set zona waktu ke Asia/Jakarta dan reset ke jam 00:00 (startOfDay)
        $tanggalPinjam = Carbon::parse($pinjaman->tanggal_pinjam)->timezone('Asia/Jakarta')->startOfDay();
        $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7);
        $tanggalPengembalian = Carbon::parse($validated['tanggal_pengembalian'])->timezone('Asia/Jakarta')->startOfDay();

        // Bandingkan murni harinya saja
        $lamaTerlambat = 0;
        if ($tanggalPengembalian->gt($tanggalHarusKembali)) {
            $lamaTerlambat = $tanggalHarusKembali->diffInDays($tanggalPengembalian);
        }

        $denda = $lamaTerlambat * 500;

        $pengembalian->update([
            'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
            'keterlambatan' => $lamaTerlambat,
            'denda' => $denda
        ]);

        // Update juga data di tabel pinjaman agar kedua halamannya sinkron
        if ($pinjaman) {
            $pinjaman->update([
                'denda' => $denda, 
                'tanggal_kembali' => $tanggalPengembalian->toDateString()
            ]);
        }

        return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $pinjaman = $pengembalian->pinjaman;
        
        // KEMBALIKAN STATUS PINJAMAN
        if ($pinjaman) {
            $pinjaman->update([
                'status' => 'belum dikembalikan', 
                'denda' => 0, 
                'tanggal_kembali' => null
            ]);
            
            // PERBAIKAN LOGIKA: Jika pengembalian dibatalkan, berarti buku MASIH DIPINJAM.
            // Jadi stok di perpustakaan harus BERKURANG, bukan bertambah.
            $buku = Book::where('judul', $pinjaman->judul_buku)->first();
            if ($buku && $buku->stok > 0) {
                $buku->decrement('stok'); 
            }
        }
        
        $pengembalian->delete();
        return redirect()->route('pengembalians.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }
}