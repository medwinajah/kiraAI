<?php

namespace App\Http\Controllers\Kategori;

use App\Http\Controllers\Controller;
use App\Models\kategoriPemasukan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriPemasukanController extends Controller
{
    public function index(){
        $kategori_pemasukan = $this->handleShowKategoriPemasukan();

        return view('pages.KategoriPemasukan.index',
        [
            'kategori_pemasukan'=>$kategori_pemasukan,
        ]);
    }

    public function handleShowKategoriPemasukan(){
        return kategoriPemasukan::all();
    }

    
public function store(Request $request)
{
    
    // 1. Validasi input
    $validated = $request->validate([
        'nama_kategori' => 'required|string|max:100',
        'deskripsi_kategori' => 'nullable|string|max:500',
    ], [
        'nama_kategori.required' =>
            'Nama kategori pemasukan wajib diisi.',

        'nama_kategori.string' =>
            'Nama kategori harus berupa teks.',

        'nama_kategori.max' =>
            'Nama kategori maksimal 100 karakter.',

        'deskripsi_kategori.string' =>
            'Deskripsi kategori harus berupa teks.',

        'deskripsi_kategori.max' =>
            'Deskripsi kategori maksimal 500 karakter.',
    ]);

    // // 2. Pastikan pengguna sudah login
    // if (!auth()->check()) {
    //     return back()->with(
    //         'error',
    //         'Silakan login terlebih dahulu.'
    //     );
    // }

    // Simpan kategori pemasukan
    try{
        KategoriPemasukan::create([
            'id_users' => Auth::user()->id,
            'nama_kategori' => trim($validated['nama_kategori']),
            'deskripsi_kategori' =>
                $validated['deskripsi_kategori'] ?? null,
        ]);
    
        // 4. Kembalikan pengguna ke halaman sebelumnya
       Alert::success('sukses','Data kategori pemasukan berhasil disimpan')->toToast('top-right');
       return redirect()->route('kategoripemasukan.index');

    }catch(Exception $er){
        Alert::error("error","Terjadi kegagalan sistem! Data Kategori Pemasukan Gagal Disimpan.")->toToast('top-right');
        return redirect()->route('kategoripemasukan.index');
    }
}

    public function update(Request $request, $id){
          $data = kategoriPemasukan::findOrFail($id);

          $validated = $request->validate([
        'nama_kategori' => 'required|string|max:100',
        'deskripsi_kategori' => 'nullable|string|max:500',
    ], [
        'nama_kategori.required' =>
            'Nama kategori pemasukan wajib diisi.',

        'nama_kategori.string' =>
            'Nama kategori harus berupa teks.',

        'nama_kategori.max' =>
            'Nama kategori maksimal 100 karakter.',

        'deskripsi_kategori.string' =>
            'Deskripsi kategori harus berupa teks.',

        'deskripsi_kategori.max' =>
            'Deskripsi kategori maksimal 500 karakter.',
    ]);
    // Simpan kategori pemasukan
    try{
      $data->update([
            'nama_kategori' => trim($validated['nama_kategori']),
            'deskripsi_kategori' =>
                $validated['deskripsi_kategori'] ?? null,
        ]);
    
        // 4. Kembalikan pengguna ke halaman sebelumnya
       Alert::success('sukses','Data kategori pemasukan berhasil diupdate')->toToast('top-right');
       return redirect()->route('kategoripemasukan.index');

    }catch(Exception $er){
        Alert::error("error","Terjadi kegagalan sistem! Data Kategori Pemasukan Gagal Disimpan.")->toToast('top-right');
        return redirect()->route('kategoripemasukan.index');
    }

    }

    public function delete($id){
        $data = kategoriPemasukan::findOrFail($id);
        try{
        $data->delete();

        Alert::success('sukses','Data kategori pemasukan berhasil dihapus')->toToast('top-right');

       return redirect()->route('kategoripemasukan.index');

        }catch(Exception $er){
         Alert::error("error","Terjadi kegagalan sistem! Data Kategori Pemasukan Gagal Dihapus.")->toToast('top-right');
        return redirect()->route('kategoripemasukan.index');
    }
        }

}
