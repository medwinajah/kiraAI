<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategoriPemasukan extends Model
{
    protected $table = 'kategori_pemasukans';

    protected $fillable = [
        'id_users',
        'nama_kategori',
        'deskripsi_kategori'
    ];

    public function Pendapatan(){
        return $this->hasMany(Pemasukan::class);
    }

    public function Users(){
        return $this->hasOne(User::class);
    }
}
