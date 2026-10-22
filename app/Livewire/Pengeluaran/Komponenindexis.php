<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

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
    public $jumlah_pagination_default = 10;
    public $jumlah_pagination_dinamis = 0;
    public $jumlah_pagination = 10;

    // updated search
    public function updatedSearch(){
        $this->search;
    }

    public function render()
    {
        $jumlah_pagination = $this->jumlah_pagination_dinamis > 0 ? $this->jumlah_pagination_dinamis : $this->jumlah_pagination_default;

        $userId = Auth::user()->id;
        $Pengeluaran = Pengeluaran::with(['Kategori'])
         ->where('id_users', $userId)
            ->when($this->tanggal_mulai, function ($query) {
                $query->whereDate('tanggal_pengeluaran', '>=', $this->tanggal_mulai);
            })->when($this->tanggal_selesai, function ($query) {
                $query->whereDate('tanggal_pengeluaran', '<=', $this->tanggal_selesai);
            })->when($this->search, function ($query) {
                $query->where(function ($q){
                    $q->where('label', "like", "%$this->search%")->orWhere('jumlah_pengeluaran', 'like', "%$this->search%")
                    ->orWhereHas('Kategori',function($qKategori){
                        $qKategori->where('nama_ketegori','like',"%$this->search%");
                    });
                });
            })->latest()->paginate($jumlah_pagination);

        return view(
            'livewire.pengeluaran.komponenindexis',
            [
                'Pengeluaran' => $Pengeluaran,
                'jumlah_paginations' => $jumlah_pagination,
            ]
        );
    }

    // Handle Reset Property
    public function resetProperty()
    {
        $this->reset(['tanggal_mulai', 'tanggal_selesai', 'search']);
    }

    // Handle Hapus Data
    public function delete($id)
    {
        $data = Pengeluaran::findOrFail($id);
        try {
            $data->delete();

            return redirect()->back()->with('success', 'Sukses menghapus data');
        } catch (\Exception $er) {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    // Handle Update
    public function update($id){
        
    }
}
