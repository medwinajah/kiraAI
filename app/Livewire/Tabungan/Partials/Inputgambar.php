<?php

namespace App\Livewire\Tabungan\Partials;

use Livewire\Component;
use Livewire\WithFileUploads;

class Inputgambar extends Component
{
    use WithFileUploads;
    public $gambar;
    public function rendering(){
        $this->reset('gambar');
    }
    
    public function render()
    {
        return view('livewire.tabungan.partials.inputgambar');
    }

    // Handle reset gambar
    public function resetGambar(){
        $this->reset('gambar');
    }
}
