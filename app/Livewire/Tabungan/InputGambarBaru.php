<?php

namespace App\Livewire\Tabungan;

use Livewire\Component;
use Livewire\WithFileUploads;

class InputGambarBaru extends Component
{
    use WithFileUploads;
    public $image;

    public function render()
    {
        return view('livewire.tabungan.input_gambar_baru');
    }

    public function resetGambar(){
        $this->reset('image');
    }
}
