<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil data anggota dengan relasi pinjamans
        $query = Anggota::with('pinjamans');

        // Filter kelas jika ada permintaan
        if ($request->kelas && $request->kelas !== 'Semua Kelas') {
            $query->where('kelas', $request->kelas);
        }

        // Ambil data dan gunakan each() agar data tetap menjadi Objek Model (bukan Array)
        $anggotas = $query->latest()->get()->each(function ($anggota) {
            // Hitung total denda dari semua pinjaman
            $totalDenda = $anggota->pinjamans->sum('denda');

            // Format denda dan tempelkan langsung sebagai properti baru di objek model
            $anggota->total_denda = $totalDenda > 0 ? 'Rp ' . number_format($totalDenda, 0, ',', '.') : 'Rp 0';
        });

        // Ambil daftar kelas unik
        $daftar_kelas = Anggota::select('kelas')->distinct()->pluck('kelas')->sort();

        return view('anggotas.index', compact('anggotas', 'daftar_kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('anggotas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Menambahkan validasi untuk NISN (Harus unik di tabel anggotas)
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:anggotas,nisn',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $anggota)
    {
        return view('anggotas.edit', compact('anggota'));
    }

    /**
     * Tampilkan riwayat peminjaman per anggota
     */
    public function peminjaman(Anggota $anggota)
    {
        $pinjamans = $anggota->pinjamans()->with('buku')->latest()->get();
        return view('anggotas.peminjaman', compact('anggota', 'pinjamans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anggota $anggota)
    {
        // Validasi update NISN, pengecualian untuk ID yang sedang aktif
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:anggotas,nisn,' . $anggota->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $anggota)
    {
        // VALIDASI KEAMANAN: Cek apakah anggota ini masih meminjam buku
        $pinjamanAktif = $anggota->pinjamans()->where('status', 'belum dikembalikan')->count();
        if ($pinjamanAktif > 0) {
            return redirect()->back()->with('error', "Gagal menghapus! Anggota {$anggota->nama} masih memiliki {$pinjamanAktif} buku yang belum dikembalikan.");
        }

        // Hapus semua data pinjaman terkait terlebih dahulu
        $anggota->pinjamans()->delete();

        // Kemudian hapus anggota
        $anggota->delete();

        return redirect()->route('anggotas.index')->with('success', 'Anggota dan semua data riwayat pinjamannya berhasil dihapus.');
    }

    /**
     * Hapus multiple anggota sekaligus (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $ids = [];

        if ($request->has('ids')) {
            $ids = $request->input('ids');
        } elseif ($request->has('selected_ids')) {
            $request->validate(['selected_ids' => 'required|json']);
            $ids = json_decode($request->selected_ids, true);
        }
        
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu anggota yang ingin dihapus!');
        }
        
        $count = 0;
        $errors = [];
        
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $anggota = Anggota::find($id);
                if ($anggota) {
                    $pinjamanAktif = $anggota->pinjamans()->where('status', 'belum dikembalikan')->count();
                    
                    if ($pinjamanAktif > 0) {
                        $errors[] = "Anggota '{$anggota->nama}' masih memiliki {$pinjamanAktif} buku yang belum dikembalikan.";
                        continue;
                    }
                    
                    $anggota->pinjamans()->delete();
                    $anggota->delete();
                    $count++;
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anggotas.index')->with('error', 'Terjadi kesalahan sistem saat menghapus massal: ' . $e->getMessage());
        }
        
        $message = "Berhasil menghapus {$count} anggota.";
        if (!empty($errors)) {
            $message .= " Catatan gagal: " . implode(' ', $errors);
            return redirect()->route('anggotas.index')->with('warning', $message);
        }
        
        return redirect()->route('anggotas.index')->with('success', $message);
    }

    /**
     * Hapus semua data anggota secara total
     */
    public function deleteAll()
    {
        $count = Anggota::count();
        
        if ($count == 0) {
            return redirect()->route('anggotas.index')->with('error', 'Tidak ada data anggota untuk dihapus.');
        }

        $anggotaDenganPinjamanAktif = Anggota::whereHas('pinjamans', function($query) {
            $query->where('status', 'belum dikembalikan');
        })->count();

        if ($anggotaDenganPinjamanAktif > 0) {
            return redirect()->route('anggotas.index')
                ->with('error', "Gagal membersihkan database! Masih ada {$anggotaDenganPinjamanAktif} anggota yang belum mengembalikan buku.");
        }
        
        try {
            Pinjaman::query()->delete();
            Anggota::query()->delete();
            
            return redirect()->route('anggotas.index')->with('success', "Berhasil membersihkan seluruh data ({$count} data anggota telah dihapus).");
                
        } catch (\Exception $e) {
            return redirect()->route('anggotas.index')->with('error', 'Gagal membersihkan data: ' . $e->getMessage());
        }
    }
}