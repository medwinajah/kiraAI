<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    protected $table = 'tabungans';

    protected $fillable = [
        'id_users',
        'label_tabungan',
        'gambar_target_menabung',
        'target_tabungan',
        'tanggal_awal_menabung',
        'tabungan_awal',
        'status_tabungan',
        'tanggal_target_tercapai',
        'deskripsi',
        'total_tabungan'
    ];

    protected $casts = [
        'tanggal_awal_menabung'=>'datetime',
        'tanggal_target_tercapai'=>'datetime',
    ];

    public function Users()
   {
      return $this->belongsTo(User::class, 'id_users', 'id');
   }
}
