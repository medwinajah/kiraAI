<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     */
    public function index()
    {
        

        return view('pages.Profile.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Menyimpan perubahan informasi profil.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // Validasi semua input sebelum data diubah.
        $validated = $request->validate(
            [
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],

                'jenis_kelamin' => [
                    'nullable',
                    Rule::in(['pria', 'wanita']),
                ],

                'profile' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
            ],
            [
                'nama.required' => 'Nama lengkap wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama maksimal 255 karakter.',

                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.unique' => 'Alamat email sudah digunakan akun lain.',
                'email.max' => 'Alamat email maksimal 255 karakter.',

                'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',

                'profile.image' => 'File yang diunggah harus berupa gambar.',
                'profile.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
                'profile.max' => 'Ukuran foto maksimal 2 MB.',
            ]
        );

        $fotoLama = $user->profile;
        $fotoBaru = null;

        /*
        |--------------------------------------------------------------------------
        | 1. Upload foto baru jika pengguna memilih file
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile')) {
            $fotoBaru = $request->file('profile')
                ->store('profiles', 'public');

            if (!$fotoBaru) {
                return back()
                    ->withInput()
                    ->with('error', 'Foto profil gagal diunggah. Silakan coba kembali.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Update data pengguna
        |--------------------------------------------------------------------------
        */

        $emailBerubah = $user->email !== $validated['email'];

        $user->name = trim($validated['nama']);
        $user->email = $validated['email'];
        $user->jenis_kelamin = $validated['jenis_kelamin'] ?? null;

        // Perbarui foto hanya jika ada foto baru.
        // Jika tidak ada file baru, foto lama tetap dipertahankan.
        if ($fotoBaru !== null) {
            $user->profile = $fotoBaru;
        }

        // Jika email berubah, status verifikasi perlu diperiksa ulang.
        if ($emailBerubah) {
            $user->email_verified_at = null;
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | 3. Hapus foto lama setelah foto baru berhasil disimpan
        |--------------------------------------------------------------------------
        */

        if (
            $fotoBaru !== null &&
            !empty($fotoLama) &&
            $fotoLama !== $fotoBaru
        ) {
            Storage::disk('public')->delete($fotoLama);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Kembali ke halaman profil dengan notifikasi
        |--------------------------------------------------------------------------
        */
        

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}