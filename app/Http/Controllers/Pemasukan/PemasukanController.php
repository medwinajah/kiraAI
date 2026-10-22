<?php

namespace App\Http\Controllers\Pemasukan;

use App\Http\Controllers\Controller;
use App\Models\kategoriPemasukan;
use App\Models\Pemasukan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PemasukanController extends Controller
{
    // Render Data
    public function index()
    {
        return view('pages.Pemasukan.index');
    }

    // Render halaman tambah
    public function create()
    {
        $kategori_pemasukan = $this->dataKategoriPemasukan();

        return view(
            'pages.Pemasukan.tambah',
            [
                'kategori_pemasukan' => $kategori_pemasukan,
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
                'jumlah_pemasukan' => 'required|numeric|min:1000',
                'sumber_pemasukan' => 'nullable|string',
                'tanggal_pemasukan' => 'required|date',
                'deskripsi_pemasukan' => 'nullable|string'
            ],
            [
                'kategori.required' => 'Kategori wajib diisi.',

                'label.required' => 'Label wajib diisi.',
                'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                'label.string' => 'Label harus berupa teks.',

                'jumlah_pemasukan.required' => 'Jumlah pemasukan wajib diisi.',
                'jumlah_pemasukan.numeric' => 'Jumlah pemasukan harus berupa angka.',
                'jumlah_pemasukan.min' => 'Jumlah pemasukan minimal adalah 1.000.',

                'sumber_pemasukan.string' => 'Sumber pemasukan harus berupa teks.',

                'tanggal_pemasukan.required' => 'Tanggal pemasukan wajib diisi.',
                'tanggal_pemasukan.date' => 'Format tanggal pemasukan tidak valid.',

                'deskripsi_pemasukan.string' => 'Deskripsi pemasukan harus berupa teks.',
            ]

        );

        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        try {
            Pemasukan::create(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pemasukan' => $validasi['jumlah_pemasukan'],
                    'sumber_pemasukan' => $validasi['sumber_pemasukan'],
                    'tanggal_pemasukan' => $validasi['tanggal_pemasukan'] . ' ' . $jam_terkini,
                    'deskripsi_pemasukan' => $validasi['deskripsi_pemasukan'],
                    'id_kategori' => $validasi['kategori'],
                ],
                 [
                'kategori.required' => 'Kategori wajib diisi.',

                'label.required' => 'Label wajib diisi.',
                'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                'label.string' => 'Label harus berupa teks.',

                'jumlah_pemasukan.required' => 'Jumlah pemasukan wajib diisi.',
                'jumlah_pemasukan.numeric' => 'Jumlah pemasukan harus berupa angka.',
                'jumlah_pemasukan.min' => 'Jumlah pemasukan minimal adalah 1.000.',

                'sumber_pemasukan.string' => 'Sumber pemasukan harus berupa teks.',

                'tanggal_pemasukan.required' => 'Tanggal pemasukan wajib diisi.',
                'tanggal_pemasukan.date' => 'Format tanggal pemasukan tidak valid.',

                'deskripsi_pemasukan.string' => 'Deskripsi pemasukan harus berupa teks.',
            ]
            );

            //    Sukses
            Alert::success('Success', 'Data pemasukan berhasil disimpan.');
            return back();
        } catch (\Exception $er) {
            Alert::error('Error', "Mohon maaf data gagal ditambahkan!");
            Logger("Error Tambah Data Pemasukan: " + $er->getMessage());
        }
    }

    // Handle Load Jenis Kategori
    public function dataKategoriPemasukan()
    {
        return kategoriPemasukan::all();
    }

    // Handle Edit
    public function edit($id)
    {
        $data = Pemasukan::findOrFail($id);
        $kategori = kategoriPemasukan::all();
        return view('pages.Pemasukan.edit', [
            'pemasukan' => $data,
            'kategori_pemasukan' => $kategori,
        ]);
    }

    // Handle Update
    public function update(Request $request, $id)
    {
        $data = Pemasukan::findOrFail($id);
        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pemasukan' => 'required|numeric|min:1000',
                'sumber_pemasukan' => 'nullable|string',
                'tanggal_pemasukan' => 'required|date',
                'deskripsi_pemasukan' => 'nullable|string'
            ]
        );
        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        $tanggal_lama = $data->tanggal_pemasukan->format('Y-m-d');
        if ($request->tanggal_pemasukan == $tanggal_lama) {
            $validasi['tanggal_pemasukan'] = $data->tanggal_pemasukan;
        } else {
            $validasi['tanggal_pemasukan'] =  $request->tanggal_pemasukan . ' ' . $jam_terkini;
        }

        try {
            $data->update(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pemasukan' => $validasi['jumlah_pemasukan'],
                    'sumber_pemasukan' => $validasi['sumber_pemasukan'],
                    'tanggal_pemasukan' => $validasi['tanggal_pemasukan'],
                    'deskripsi_pemasukan' => $validasi['deskripsi_pemasukan'],
                    'id_kategori' => $validasi['kategori'],
                ]
            );

            //    Sukses
            Alert::success('Success', 'Data pemasukan berhasil disimpan.')->toToast('top-right');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $er) {

            logger("Error Tambah Data Pemasukan: " . $er->getMessage());
            // PERBAIKAN: Menambahkan return back agar halaman tidak putih kosong saat eror
            Alert::error('Error', "Mohon maaf data gagal diperbarui!");
            return back()->withInput();
        }
    }
}
