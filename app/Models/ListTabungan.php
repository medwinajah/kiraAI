<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListTabungan extends Model
{
    protected $table = 'list_tabungans';

    protected $fillable = [
        'id_tabungan',
        'tambahan_tabungan',
        'tanggal_tambahan'
    ];

    protected $casts = [
        'tanggal_tambahan'=>'datetime',
    ];
}
