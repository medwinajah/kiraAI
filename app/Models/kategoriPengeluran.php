<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategoriPengeluran extends Model
{
    protected $table = 'kategori_pengelurans';

    protected $fillable = [
        'id_users',
        'nama_ketegori',
        'deskripsi_kategori'
    ];

     public function Pengeluaran(){
        return $this->hasMany(Pengeluaran::class);
    }

    public function Users(){
        return $this->hasOne(User::class);
    }
}
