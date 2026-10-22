<?php

namespace App\Http\Controllers\Tabungan;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ControllerTabungan extends Controller
{
    public function index()
    {
         // Jumlah Selesai
        $selesai = $this->sumJumlahSelesai();
        $proses = $this->sumJumlahProses();
        $semua = $this->sumJumlahSeluruhnya();

        return view('pages.Tabungan.index',[
            'jumlah_selesai'=>$selesai,
            'jumlah_proses'=>$proses,
            'jumlah_semua'=>$semua,
        ]);
    }

    public function create()
    {
        return view('pages.Tabungan.tambahtabungan');
    }

    public function store(Request $request)
    {
        
        $validasi = $request->validate(
            [
                'label_tabungan' => 'required|min:4|string',
                'gambar_target_menabung' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'target_tabungan' => 'required|min:1000|numeric',
                'tabungan_awal' => "required|min:1000|numeric",
                'tanggal_awal_menabung' => 'required|date',
                'deskripsi' => 'nullable',
            ],
            [

                'label_tabungan.required' => 'Nama atau label tabungan wajib diisi.',
                'label_tabungan.min'      => 'Label tabungan minimal harus terdiri dari 4 karakter.',
                'label_tabungan.string'   => 'Label tabungan harus berupa teks.',

                'gambar_target_menabung.image' => 'File harus berupa gambar.',
                'gambar_target_menabung.mimes' => 'Format gambar harus berupa PNG, JPG, atau JPEG.',
                'gambar_target_menabung.max'   => 'Ukuran gambar maksimal adalah 2 MB.',

                'target_tabungan.required' => 'Nominal target tabungan wajib diisi.',
                'target_tabungan.min'      => 'Target tabungan minimal sebesar Rp 1.000.',
                'target_tabungan.numeric'  => 'Target tabungan harus berupa angka.',

                'tabungan_awal.required' => 'Nominal tabungan awal wajib diisi.',
                'tabungan_awal.min'      => 'Tabungan awal minimal sebesar Rp 1.000.',
                'tabungan_awal.numeric'  => 'Tabungan awal harus berupa angka.',

                'tanggal_awal_menabung.required' => 'Tanggal awal menabung wajib diisi.',
                'tanggal_awal_menabung.date'     => 'Format tanggal awal menabung tidak valid.',
            ]
        );
        // Handle Tabungan (Butuh AuthID);
        $pemasukan =(int) Pemasukan::where('id_users',Auth::user()->id)->sum('jumlah_pemasukan');
        if(!$pemasukan || $pemasukan==0){
            Alert::warning('warning','Jumlah pemasukan anda tidak ditemukan')->toToast('top-right');
            return redirect()->route('tabungan.index');
        }

$userId = Auth::user()->id;

// Total pemasukan pengguna
$pemasukan = (int) Pemasukan::where('id_users', $userId)
    ->sum('jumlah_pemasukan');

// Total pengeluaran pengguna
$pengeluarans = (int) Pengeluaran::where('id_users', $userId)
    ->sum('jumlah_pengeluaran');

// Total saldo seluruh tabungan pengguna
$tabungan = (int) Tabungan::where('id_users', $userId)
    ->sum('total_tabungan');

// Hitung saldo akhir
$saldo_akhir = $pemasukan - ($pengeluarans + $tabungan);

// Nominal tabungan awal yang hendak dibuat
$tabunganBaru = (int) $validasi['tabungan_awal'];

// Validasi apakah saldo mencukupi
if ($tabunganBaru > $saldo_akhir) {
    Alert::warning(
        'Saldo Tidak Mencukupi',
        'Saldo akhir yang kamu miliki tidak mencukupi untuk membuat tabungan baru.'
    )->toToast('top-right');

    return redirect()->route('tabungan.index')->withInput();
}

        // Handle mising gambar
        $validasi['gambar_target_menabung'] = null;
        // Logika Handel Gambar
        if ($request->hasFile('gambar_target_menabung')) {
            $validasi['gambar_target_menabung'] = $request->file('gambar_target_menabung')->store('Gambar_Target_Menabung', 'public');
        }
        // Simpan data kedatabase
        try {
            Tabungan::create(
                [
                    'id_users' => Auth::user()->id,
                    'label_tabungan'=>$validasi['label_tabungan'],
                    'gambar_target_menabung' => $validasi['gambar_target_menabung'],
                    'target_tabungan' => $validasi['target_tabungan'],
                    'tanggal_awal_menabung' => $validasi['tanggal_awal_menabung']??null,
                    'tabungan_awal' => $validasi['tabungan_awal'],
                    'status_tabungan' => 'proses',
                    'deskripsi' => $validasi['deskripsi'],
                    'total_tabungan'=>$validasi['tabungan_awal'],
                ]
            );

            Alert::success('Sukses','Tabungan anda berhasil dibuat!')->toToast('top-right');

            return redirect()->route('tabungan.index');
        } catch (\Exception $er) {
            logger('Error Sistem Tambah Tabungan: ' . $er->getMessage());
            Alert::error('Error','Terjadi kesalahan sistem! Data tabungan gagal ditambahkan.')->toToast('top-right');
             return redirect()->route('tabungan.index');
        }
    }

     public function sumJumlahProses()
{
    return Tabungan::where('id_users', Auth::id())
        ->whereIn('status_tabungan', ['new', 'proses'])
        ->count();
}

public function sumJumlahSelesai()
{
    return Tabungan::where('id_users', Auth::id())
        ->where('status_tabungan', 'sukses')
        ->count();
}

public function sumJumlahSeluruhnya()
{
    return Tabungan::where('id_users', Auth::id())
        ->count();
}

   
}
