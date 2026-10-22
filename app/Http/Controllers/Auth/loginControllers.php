<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class loginControllers extends Controller
{
    public function index(){
        return view('Auth.Login');
    }

    public function auth(Request $request)
{
    // 1. Validasi input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ]);

    // 2. Proses autentikasi
    if (Auth::attempt(
        $credentials,
        $request->boolean('remember')
    )) {

        // 3. Perbarui session untuk keamanan
        $request->session()->regenerate();
 
        Alert::success('Hallo '.Auth::user()->name,'Selamat datang kembali!');
        // 4. Arahkan pengguna ke dashboard
        return redirect()->intended(route('dashboard.home'))
            ->with('success', 'Login berhasil. Selamat datang kembali!');
    }

    // 5. Jika email atau password salah
    Alert::warning('Warning','Email atau password yang anda masukan salah!')->toToast('top-right');
    return back()
        ->withErrors([
            'email' => 'Email atau password yang anda masukan salah.',
        ])
        ->onlyInput('email');
}

    public function logout(Request $request)
{
    Auth::logout();

    // Hapus session untuk keamanan
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    Alert::success('sukses','Logout berhasil dilakukan!')->toToast('top-right');
    return redirect()->route('login');
}
}
