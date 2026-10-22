<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
   protected $table = 'pengeluarans';

   protected $fillable = [
      'id_users',
      'id_kategori',
      'label',
      'jumlah_pengeluaran',
      'deskripsi_pengeluaran',
      'tanggal_pengeluaran',
   ];
   protected function casts(): array
   {
      return [
         'tanggal_pengeluaran' => 'datetime',
      ];
   }

   public function Kategori()
   {
      return $this->belongsTo(kategoriPengeluran::class, 'id_kategori', 'id');
   }

   public function Users()
   {
      return $this->belongsTo(User::class, 'id_users', 'id');
   }
}
