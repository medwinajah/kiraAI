<?php

namespace App\Livewire\Pemasukan;

use App\Models\Pemasukan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use RealRashid\SweetAlert\Facades\Alert;

class Komponenindexis extends Component
{
    // Membuat Pagination
    use WithPagination;
    // Atur Thema Pagination
    protected $paginationTheme = 'bootstrap';

    // Property Filter Tanggal
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';

    // Property Input Search
    public $search = '';

    // Property Setings Jumlah Pagination
    public $jumlah_pagination_default=10;
    public $jumlah_pagination_dinamis=0;
    public $jumlah_pagination = 10;

     // updated search
    public function updatedSearch(){
        $this->search;
    }
    
    public function render()
    {
        $jumlah_pagination = $this->jumlah_pagination_dinamis > 0 ? $this->jumlah_pagination_dinamis : $this->jumlah_pagination_default;

        // Mengambil ID User yang sedang login secara aman
$userId = Auth::user()->id;

$pemasukan = Pemasukan::with(['Kategori'])
    ->where('id_users', $userId)
    ->when($this->tanggal_mulai, function($query) {
        $query->whereDate('tanggal_pemasukan', '>=', $this->tanggal_mulai);
    })
    ->when($this->tanggal_selesai, function($query) {
        $query->whereDate('tanggal_pemasukan', '<=', $this->tanggal_selesai);
    })
    ->when($this->search, function($query) {
        $query->where(function($subQuery) {
            $subQuery->where('label', 'like', "%{$this->search}%")
                     ->orWhere('jumlah_pemasukan', 'like', "%{$this->search}%");
        });
    })
    ->latest()
    ->paginate($jumlah_pagination);

        return view('livewire.pemasukan.komponenindexis',
        [
            'pemasukan'=>$pemasukan,
            'jumlah_paginations'=>$jumlah_pagination,
        ]);
    }

    // Handle Reset Property
    public function resetProperty(){
        $this->reset(['tanggal_mulai','tanggal_selesai','search']);
    }

    // Handle Hapus Data
    public function delete($id){
        $data = Pemasukan::findOrFail($id);
        try{
        $data->delete();
       
        return redirect()->back()->with('success', 'Sukses menghapus data');
        }catch(\Exception $er){
              return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

}
