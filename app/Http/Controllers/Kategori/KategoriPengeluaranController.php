<?php

namespace App\Http\Controllers\Kategori;

use App\Http\Controllers\Controller;
use App\Models\kategoriPengeluran;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriPengeluaranController extends Controller
{
    public function index(){
        $kategori_pengeluaran = $this->handleShowKategoriPengeluaran();

        return view('pages.KategoriPengeluaran.index',
        [
            'kategori_pengeluaran'=>$kategori_pengeluaran,
        ]);
    }

    public function handleShowKategoriPengeluaran(){
        return kategoriPengeluran::all();
    }


    public function store(Request $request)
{
    

    // 1. Validasi input
    $validated = $request->validate([
        'nama_kategori' => 'required|string|max:100',
        'deskripsi_kategori' => 'nullable|string|max:500',
    ], [
        'nama_kategori.required' =>
            'Nama kategori pengeluaran wajib diisi.',

        'nama_kategori.string' =>
            'Nama kategori harus berupa teks.',

        'nama_kategori.max' =>
            'Nama kategori maksimal 100 karakter.',

        'deskripsi_kategori.string' =>
            'Deskripsi kategori harus berupa teks.',

        'deskripsi_kategori.max' =>
            'Deskripsi kategori maksimal 500 karakter.',
    ]);

    // 2. Simpan kategori pengeluaran
    try {
        kategoriPengeluran::create([
            'id_users' => Auth::user()->id,
            'nama_ketegori' => trim($validated['nama_kategori']),
            'deskripsi_kategori' =>
                $validated['deskripsi_kategori'] ?? null,
        ]);

        Alert::success(
            'Sukses',
            'Data kategori pengeluaran berhasil disimpan.'
        )->toToast('top-right');

        return redirect()->route('kategoripengeluaran.index');

    } catch (\Exception $er) {
        report($er);

        Alert::error(
            'Error',
            'Terjadi kegagalan sistem! Data kategori pengeluaran gagal disimpan.'.$er->getMessage()
        )->toToast('top-right');

        return redirect()->route('kategoripengeluaran.index');
    }
}


public function update(Request $request, $id){
          $data = kategoriPengeluran::findOrFail($id);

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
            'nama_ketegori' => trim($validated['nama_kategori']),
            'deskripsi_kategori' =>
                $validated['deskripsi_kategori'] ?? null,
        ]);
    
        // 4. Kembalikan pengguna ke halaman sebelumnya
       Alert::success('sukses','Data kategori pengeluaran berhasil diupdate')->toToast('top-right');
       return redirect()->route('kategoripengeluaran.index');

    }catch(Exception $er){
        Alert::error("error","Terjadi kegagalan sistem! Data Kategori Pengeluaran Gagal Disimpan.")->toToast('top-right');
        return redirect()->route('kategoripengeluaran.index');
    }

    }

    public function delete($id){
        $data = kategoriPengeluran::findOrFail($id);
        try{
        $data->delete();

        Alert::success('sukses','Data kategori pengeluaran berhasil dihapus')->toToast('top-right');

       return redirect()->route('kategoripengeluaran.index');

        }catch(Exception $er){
         Alert::error("error","Terjadi kegagalan sistem! Data Kategori pengeluaran Gagal Dihapus.")->toToast('top-right');
        return redirect()->route('kategoripengeluaran.index');
    }
        }



}
