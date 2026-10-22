<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class registerControllers extends Controller
{
     public function index(){
        return view('Auth.Register');
    }

   
public function store(Request $request)
{
    
    // 1. Validasi data registrasi
    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:255|unique:users,email',
        'jenis_kelamin' => 'required|in:pria,wanita', // Validasi input dari form Blade Anda
        'profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'nama.string' => 'Nama harus berupa teks.',
        'nama.max' => 'Nama maksimal 100 karakter.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format alamat email tidak valid.',
        'email.max' => 'Alamat email maksimal 255 karakter.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'jenis_kelamin.required' => 'Silakan pilih jenis kelamin.',
        'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
        'profile.image' => 'File profil harus berupa gambar.',
        'profile.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
        'profile.max' => 'Ukuran foto profil maksimal 2 MB.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    try{
        // 2. Upload foto profil jika tersedia
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profile', 'public');
        }
       
       
    
        User::create([
            'name'          => $validated['nama'],   
            'email'         => $validated['email'],
            'jenis_kelamin' => $validated['jenis_kelamin'],     
            'profile'       => $profilePath,
            'password'      => Hash::make($validated['password']),
            'hak_akses'     => 'users',             
        ]);
    
        Alert::success('sukses','Akun anda berhasil dibuat! Silahkan login.')->toToast('top-right');
        
        // 4. Arahkan ke halaman login
        return redirect()->route('login');

    }catch(Exception $er){
        Alert::error('Error',"-".$er->getMessage());
        return redirect()->route('register.index');
    }
}

}
