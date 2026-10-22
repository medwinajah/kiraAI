<?php

namespace App\Livewire\Pengeluaran\Partials;

use Livewire\Component;

class Formnumber extends Component
{
    public  $jumlah_pengeluarans = 0;
    public  $jumlah_pengeluaran;

    public function mount($jumlah_pengeluarans = null){
        // Menyimpan data kiriman ke properti kelas
        $this->jumlah_pengeluarans = $jumlah_pengeluarans;
      // Logika Ternary bertingkat: Cek kiriman -> Cek Old Input -> Default 0
        $this->jumlah_pengeluaran = $this->jumlah_pengeluarans 
            ? $this->jumlah_pengeluarans 
            : (old('jumlah_pengeluarans') ? old('jumlah_pengeluarans') : 0);
    }

    public function render()
    {
        return view('livewire.pengeluaran.partials.formnumber');
    }
}
