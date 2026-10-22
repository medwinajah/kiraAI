<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $table = 'pemasukans';

    protected $fillable = [
        'id_users',
        'id_kategori',
        'label',
        'jumlah_pemasukan',
        'sumber_pemasukan',
        'tanggal_pemasukan',
        'deskripsi_pemasukan'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemasukan'=>'datetime',
        ];
    }

    public function Kategori(){
        return $this->belongsTo(kategoriPemasukan::class,'id_kategori','id');
    }

    public function Users(){
        return $this->belongsTo(User::class,'id_users','id');
    }
}
