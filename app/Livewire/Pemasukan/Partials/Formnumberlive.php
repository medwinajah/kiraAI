<?php

namespace App\Livewire\Pemasukan\Partials;

use Livewire\Component;

class Formnumberlive extends Component
{
    public  $jumlah_pemasukan = 0;
    public  $jumlah_pemasukans;

    public function mount($jumlah_pemasukans = null){
        // Menyimpan data kiriman ke properti kelas
        $this->jumlah_pemasukans = $jumlah_pemasukans;
      // Logika Ternary bertingkat: Cek kiriman -> Cek Old Input -> Default 0
        $this->jumlah_pemasukan = $this->jumlah_pemasukans 
            ? $this->jumlah_pemasukans 
            : (old('jumlah_pemasukan') ? old('jumlah_pemasukan') : 0);
    }
    public function render()
    {
        return view('livewire.pemasukan.partials.formnumberlive');
    }

}
