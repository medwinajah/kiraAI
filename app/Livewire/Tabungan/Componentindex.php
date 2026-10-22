<?php

namespace App\Livewire\Tabungan;

use App\Models\ListTabungan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Tabungan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;

class Componentindex extends Component
{
    use WithFileUploads;

    public $persen;

    // Filter Data
    public $status_tabungan;
    public $search;

    //     Property untuk edit tabungan
    public $tabungan_id;
    public $label_tabungan;
    public $target_tabungan;
    public $tanggal_awal_menabung;
    public $tabungan_awal;
    public $deskripsi;
    public $gambar_baru;
    public $gambar_lama;
    // Property Tambah Tabungan
    public $nominal_top_up = 0;

    public function render()
    {
        $userId = Auth::user()->id;
        $tabungan = Tabungan::query()
            ->where('id_users', $userId)
            ->when($this->status_tabungan, function ($query) {
                $query->where('status_tabungan', $this->status_tabungan);
            })->when($this->search, function ($query) {
                $query->where('label_tabungan', 'like', "%$this->search%");
            })->latest()->paginate(10);

        $this->ProgresBarr($tabungan);

        // Jumlah Selesai
        $selesai = $this->sumJumlahSelesai();
        $proses = $this->sumJumlahProses();
        $semua = $this->sumJumlahSeluruhnya();

        return view('livewire.tabungan.componentindex', [
            'tabungan' => $tabungan,
            'jumlah_selesai' => $selesai,
            'jumlah_proses' => $proses,
            'jumlah_semua' => $semua,
        ]);
    }

    public function ProgresBarr($tabungan)
    {
        // Memanipulasi tiap baris data di dalam paginator
        $tabungan->getCollection()->transform(function ($data) {
            // Logika perhitungan persen
            $data->persen = ($data->target_tabungan > 0)
                ? min(($data->total_tabungan / $data->target_tabungan) * 100, 100)
                : 0;
            return $data;
        });

        return $tabungan;
    }

    public function deleteTabungan($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        try {
            $tabungan->delete();


            Alert::success('Sukses', 'Tabungan berhasil dihapus!')->toToast('top-right');
            return redirect()->route('tabungan.index');
        } catch (Exception $er) {
            logger('Error hapus data tabungan: ' . $er->getMessage());
            Alert::warning('error', 'Terjadi kesalahan sistem! Tabungan gagal dihapus')->toToast('top-right');
            return redirect()->route('tabungan.index');
        }
    }

    // Handle TOP-UP
    public function topup($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        // Handle Jika Tabungan ada dan nominal_top_up tidak kosong
        if ($tabungan and ($this->nominal_top_up != null or $this->nominal_top_up != '')) {

            $total_tabungan = (int) $tabungan->total_tabungan;
            $pengeluaran = (int) Pengeluaran::sum('jumlah_pengeluaran');
            $jumlah_pemasukan = (int) Pemasukan::sum('jumlah_pemasukan');

            // Perhitungan Saldo Akhir
            $saldo_akhir = (int) $jumlah_pemasukan - ($total_tabungan + $pengeluaran);

            $batas_saldo = 20000;
            // Validasi Saldo Tidak Mencukupi
            if ($saldo_akhir <= $batas_saldo || $saldo_akhir <= $this->nominal_top_up) {
                Alert::info('Informasi', 'Jumlah saldo yang anda miliki tidak mencukupi untuk melakukan TOP-UP Tabungan')->toToast();
                return redirect()->route('tabungan.index');
            }

            DB::beginTransaction();
            try {
                // Validasi total tabungan lebih mencukupi melakukan TOP-UP
                ListTabungan::create(
                    [
                        'id_tabungan' => $tabungan->id,
                        'tambahan_tabungan' => $this->nominal_top_up,
                        'tanggal_tambahan' => now(),
                    ]
                );

                // Update Total Tabungan 
                $total_baru = (int) $total_tabungan + $this->nominal_top_up;
                $tabungan->update(
                    ['total_tabungan' => $total_baru]
                );

                // Reset Input nominal top-up
                $this->reset('nominal_top_up');
                DB::commit();

                Alert::success('Sukses', 'TOP-UP Tabungan Berhasil Dilakukan!')->toToast('top-right');
                return redirect()->route('tabungan.index');
            } catch (Exception $er) {
                DB::rollBack();
                Alert::error('error', 'Terjadi kesalahan sistem!.')->toToast('top-right');
                return redirect()->route('tabungan.index');
            }
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
    public function handleTabunganPenuh()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::user()->id;

        if (!$userId) {
            return;
        }

        // Ambil tabungan milik user tersebut
        $tabunganUser = Tabungan::where('id_users', $userId)->get();

        foreach ($tabunganUser as $tabungan) {

            // Hitung seluruh top-up untuk target tabungan ini
            $totalTopUp = ListTabungan::where(
                'id_tabungan',
                $tabungan->id
            )->sum('tambahan_tabungan');

            // Hitung total tabungan saat ini
            $totalTabungan = (int) $tabungan->tabungan_awal
                + (int) $totalTopUp;

            // Jika target sudah tercapai
            if (
                $totalTabungan >= (int) $tabungan->target_tabungan
                && $tabungan->status_tabungan !== 'sukses'
            ) {
                $tabungan->update([
                    'status_tabungan' => 'sukses',
                    'tanggal_target_tercapai' =>
                    $tabungan->tanggal_target_tercapai
                        ?? now()->toDateString(),
                ]);
            }
        }
    }

    public function edit($id)
    {
        $data_tabungan = Tabungan::findOrFail($id);
        $this->tabungan_id = $data_tabungan->id;
        $this->label_tabungan = $data_tabungan->label_tabungan;
        $this->target_tabungan = $data_tabungan->target_tabungan;
        $this->tanggal_awal_menabung = $data_tabungan->tanggal_awal_menabung;
        $this->tabungan_awal = $data_tabungan->tabungan_awal;
        $this->deskripsi = $data_tabungan->deskripsi;
        $this->gambar_lama = $data_tabungan?->gambar_target_menabung;
        $this->gambar_baru = null;
    }

    public function update($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $validasi = $this->validate(
            [
        'tabungan_id' => 'required|integer|exists:tabungans,id',
        'label_tabungan' => 'required|string|max:255',
        'target_tabungan' => 'required|numeric|min:1000',
        'tanggal_awal_menabung' => 'required|date|after_or_equal:today',
        'tabungan_awal' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string|max:1000',
        'gambar_baru' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ],
    [
        'tabungan_id.required' => 'ID tabungan wajib tersedia.',
        'tabungan_id.integer' => 'ID tabungan tidak valid.',
        'tabungan_id.exists' => 'Data tabungan tidak ditemukan.',

        'label_tabungan.required' => 'Nama tabungan wajib diisi.',
        'label_tabungan.string' => 'Nama tabungan harus berupa teks.',
        'label_tabungan.max' => 'Nama tabungan maksimal 255 karakter.',

        'target_tabungan.required' => 'Target menabung wajib diisi.',
        'target_tabungan.numeric' => 'Target menabung harus berupa angka.',
        'target_tabungan.min' => 'Target menabung minimal Rp1.000.',

        'tanggal_awal_menabung.required' => 'Tanggal awal menabung wajib diisi.',
        'tanggal_awal_menabung.date' => 'Tanggal awal menabung tidak valid.',
        'tanggal_awal_menabung.after_or_equal' => 'Tanggal awal menabung tidak boleh sebelum hari ini.',

        'tabungan_awal.required' => 'Nominal tabungan awal wajib diisi.',
        'tabungan_awal.numeric' => 'Nominal tabungan awal harus berupa angka.',
        'tabungan_awal.min' => 'Nominal tabungan awal tidak boleh kurang dari Rp0.',

        'deskripsi.string' => 'Deskripsi tabungan harus berupa teks.',
        'deskripsi.max' => 'Deskripsi tabungan maksimal 1.000 karakter.',

        'gambar_baru.image' => 'File yang diunggah harus berupa gambar.',
        'gambar_baru.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
        'gambar_baru.max' => 'Ukuran gambar maksimal 2 MB.',
    ]);
        try {
        $path_gambar = $tabungan->gambar_target_menabung;
        // Periksa apakah ada gambar baru
       if($this->gambar_baru !=null){
        if($this->gambar_lama !=null || $this->gambar_lama !=''){
                // Hapus gambar lama
                if(Storage::disk('public')->exists($this->gambar_lama)){
                    Storage::disk('public')->delete($this->gambar_lama);
                }
            $path_gambar = $this->gambar_baru->store('Gambar_Targe_Menabung','public');
        }
       }

       $tabungan->update([
    'label_tabungan' => $this->label_tabungan,
    'target_tabungan' => $this->target_tabungan,
    'tanggal_awal_menabung' => $this->tanggal_awal_menabung,
    'tabungan_awal' => $this->tabungan_awal,
    'deskripsi' => $this->deskripsi,
    'gambar_target_menabung' => $path_gambar,
       ]);

       Alert::success('sukses','Data Tabungan Berhasil Diperbarui')->toToast('top-right');
    return redirect()->route('tabungan.index');

        } catch (\Exception $er) {
            Alert::error('error','Data Tabungan Tidak Berhail Diperbarui')->toToast('top-right');
            return redirect()->route('tabungan.index');
        }
    }

    public function resetGambar()
    {
        $this->reset('gambar_baru');
    }
}
