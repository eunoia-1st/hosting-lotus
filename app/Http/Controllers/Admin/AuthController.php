<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login admin.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan view ini ada
    }

    /**
     * Menangani proses login admin.
     */
    public function login(Request $request)
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'username' => ['required', 'string'], // Diubah dari email
            'password' => ['required']
        ]);

        // 2. Coba lakukan otentikasi menggunakan guard 'admin'
        //    Parameter kedua (boolean) adalah untuk fitur "Remember Me"
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard admin
            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // 3. Jika otentikasi gagal, kembalikan ke halaman login
        //    Kirim pesan error khusus untuk field 'username'
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.'
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Anda berhasil logout.');
    }
}
