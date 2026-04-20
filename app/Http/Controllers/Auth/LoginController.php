<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        // Cek kredensial (hardcoded)
        if ($credentials['username'] === 'perpusSDNBeratWetan1' && $credentials['password'] === 'pustakawanSDNBW1') {
            // Set session untuk middleware
            session(['logged_in' => true]);
            
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            return redirect()->route('dashboard')->with('success', 'Selamat datang, Pustakawan!');
        }

        // Jika login gagal
        return redirect()->back()
            ->with('error', 'Username atau password salah.')
            ->withInput($request->except('password'));
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Hapus semua session
        session()->flush();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}