<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data anggota dengan relasi pinjamans
        $query = Anggota::with('pinjamans');

        // Filter kelas jika ada permintaan
        if ($request->kelas) {
            $query->where('kelas', $request->kelas);
        }

        // Ambil data dan tambahkan informasi peminjaman
        $anggotas = $query->latest()->get()->map(function ($anggota) {
            $pinjamans = $anggota->pinjamans;

            // Hitung total denda dari semua pinjaman
            $totalDenda = $pinjamans->sum('denda');

            // Format denda
            $formattedDenda = $totalDenda > 0 ? 'Rp ' . number_format($totalDenda, 0, ',', '.') : 'Rp 0';

            return [
                'anggota' => $anggota,
                'pinjamans' => $pinjamans,
                'total_denda' => $formattedDenda
            ];
        });

        // Ambil daftar kelas unik
        $daftar_kelas = Anggota::select('kelas')->distinct()->pluck('kelas');

        return view('anggotas.index', compact('anggotas', 'daftar_kelas'));
    }

    public function create()
    {
        return view('anggotas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggotas.edit', compact('anggota'));
    }

    public function peminjaman(Anggota $anggota)
    {
        $pinjamans = $anggota->pinjamans()->with('buku')->latest()->get();
        return view('anggotas.peminjaman', compact('anggota', 'pinjamans'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        // Hapus semua pinjaman terkait anggota ini terlebih dahulu
        $anggota->pinjamans()->delete();

        // Kemudian hapus anggota
        $anggota->delete();

        return redirect()->route('anggotas.index')->with('success', 'Anggota dan semua data pinjamannya berhasil dihapus.');
    }

    /**
     * Hapus multiple anggota sekaligus (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|json'
        ]);
        
        $ids = json_decode($request->selected_ids);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada anggota yang dipilih.');
        }
        
        $count = 0;
        $errors = [];
        
        foreach ($ids as $id) {
            $anggota = Anggota::find($id);
            if ($anggota) {
                try {
                    // Cek apakah anggota memiliki pinjaman aktif
                    $pinjamanAktif = $anggota->pinjamans()->where('status', 'belum dikembalikan')->count();
                    
                    if ($pinjamanAktif > 0) {
                        $errors[] = "Anggota {$anggota->nama} masih memiliki {$pinjamanAktif} buku yang belum dikembalikan.";
                        continue;
                    }
                    
                    // Hapus semua pinjaman terkait
                    $anggota->pinjamans()->delete();
                    // Hapus anggota
                    $anggota->delete();
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal menghapus anggota {$anggota->nama}: " . $e->getMessage();
                }
            }
        }
        
        $message = "Berhasil menghapus {$count} anggota.";
        if (!empty($errors)) {
            $message .= " " . implode(' ', $errors);
            return redirect()->route('anggotas.index')->with('warning', $message);
        }
        
        return redirect()->route('anggotas.index')->with('success', $message);
    }

    /**
     * Hapus semua anggota (untuk tahun ajaran baru)
     */
    public function deleteAll()
    {
        $count = Anggota::count();
        
        if ($count == 0) {
            return redirect()->route('anggotas.index')
                ->with('error', 'Tidak ada anggota untuk dihapus.');
        }

        // Cek apakah ada anggota dengan pinjaman aktif
        $anggotaDenganPinjamanAktif = Anggota::whereHas('pinjamans', function($query) {
            $query->where('status', 'belum dikembalikan');
        })->count();

        if ($anggotaDenganPinjamanAktif > 0) {
            return redirect()->route('anggotas.index')
                ->with('error', "Tidak dapat menghapus semua anggota. Masih ada {$anggotaDenganPinjamanAktif} anggota dengan pinjaman aktif.");
        }
        
        try {
            // Hapus semua pinjaman terlebih dahulu
            Pinjaman::truncate();
            
            // Hapus semua anggota
            Anggota::truncate();
            
            return redirect()->route('anggotas.index')
                ->with('success', "Berhasil menghapus semua anggota ({$count} anggota).");
                
        } catch (\Exception $e) {
            return redirect()->route('anggotas.index')
                ->with('error', 'Gagal menghapus semua anggota: ' . $e->getMessage());
        }
    }
}