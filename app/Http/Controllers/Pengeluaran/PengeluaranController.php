<?php

namespace App\Http\Controllers\Pengeluaran;

use App\Http\Controllers\Controller;
use App\Models\kategoriPengeluran;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PengeluaranController extends Controller
{
     // Render Data
    public function index()
    {
        return view('pages.Pengeluaran.index');
    }

    // Render halaman tambah
    public function create()
    {
        $kategori_pengeluaran = $this->dataKategoriPengeluaran();

        return view(
            'pages.Pengeluaran.tambah',
            [
                'kategori_pengeluaran' => $kategori_pengeluaran,
            ]
        );
    }

    // Handle simpan data
    public function store(Request $request)
    {
        
        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pengeluaran' => 'required|numeric|min:1000',
                'tanggal_pengeluaran' => 'required|date',
                'deskripsi_pengeluaran' => 'nullable|string'
            ],
            [
                'kategori.required' => 'Kategori wajib diisi.',

                'label.required' => 'Label wajib diisi.',
                'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                'label.string' => 'Label harus berupa teks.',

                'jumlah_pengeluaran.required' => 'Jumlah pengeluaran wajib diisi.',
                'jumlah_pengeluaran.numeric' => 'Jumlah pengeluaran harus berupa angka.',
                'jumlah_pengeluaran.min' => 'Jumlah pengeluaran minimal adalah 1.000.',

                'tanggal_pengeluaran.required' => 'Tanggal pengeluaran wajib diisi.',
                'tanggal_pengeluaran.date' => 'Format tanggal pengeluaran tidak valid.',

                'deskripsi_pengeluaran.string' => 'Deskripsi pengeluaran harus berupa teks.',
            ]

        );

        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        try {
            Pengeluaran::create(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pengeluaran' => $validasi['jumlah_pengeluaran'],
                    'tanggal_pengeluaran' => $validasi['tanggal_pengeluaran'] . ' ' . $jam_terkini,
                    'deskripsi_pengeluaran' => $validasi['deskripsi_pengeluaran'],
                    'id_kategori' => $validasi['kategori'],
                ],
            );

            //    Sukses
            Alert::success('Success', 'Data pengeluaran berhasil disimpan.')->toToast('top-right');
            return back();
        } catch (\Exception $er) {
            Alert::error('Error', "Mohon maaf data gagal ditambahkan!");
            Logger("Error Tambah Data pengeluaran: " + $er->getMessage());
        }
    }

    // Handle Load Jenis Kategori
    public function dataKategoriPengeluaran()
    {
        return kategoriPengeluran::all();
    }

    // Handle Edit
    public function edit($id)
    {
        $data = Pengeluaran::findOrFail($id);
        $kategori = kategoriPengeluran::all();
        return view('pages.Pengeluaran.edit', [
            'pengeluaran' => $data,
            'kategori_pengeluaran' => $kategori,
        ]);
    }

    // Handle Update
    public function update(Request $request, $id)
    {
        $data = Pengeluaran::findOrFail($id);
        $users = Auth::user()->id;
        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pengeluaran' => 'required|numeric|min:1000',
                'tanggal_pengeluaran' => 'required|date',
                'deskripsi_pengeluaran' => 'nullable|string'
            ]
        );
        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        $tanggal_lama = $data->tanggal_pengeluaran->format('Y-m-d');
        if ($request->tanggal_pengeluaran == $tanggal_lama) {
            $validasi['tanggal_pengeluaran'] = $data->tanggal_pengeluaran;
        } else {
            $validasi['tanggal_pengeluaran'] =  $request->tanggal_pengeluaran . ' ' . $jam_terkini;
        }

        try {
            $data->update(
                [
                    'id_users' => $users->id,
                    'label' => $validasi['label'],
                    'jumlah_pengeluaran' => $validasi['jumlah_pengeluaran'],
                    'tanggal_pengeluaran' => $validasi['tanggal_pengeluaran'],
                    'deskripsi_pengeluaran' => $validasi['deskripsi_pengeluaran'],
                    'id_kategori' => $validasi['kategori'],
                ]
            );

            //    Sukses
            Alert::success('Success', 'Data pengeluaran berhasil disimpan.')->toToast('top-right');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $er) {

            logger("Error Tambah Data pengeluaran: " . $er->getMessage());
            // PERBAIKAN: Menambahkan return back agar halaman tidak putih kosong saat eror
            Alert::error('Error', "Mohon maaf data gagal diperbarui!");
            return back()->withInput();
        }
    }
}
