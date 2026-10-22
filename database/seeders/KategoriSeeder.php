<?php

namespace Database\Seeders;

use App\Models\kategoriPemasukan;
use App\Models\kategoriPengeluran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::first();
        if($users){
            kategoriPemasukan::create([
            'id_users' =>$users->id,
            'nama_kategori'=>"Uang Kuliah",
            'deskripsi_kategori'=>"Uang hidup untuk makan",
        ]);
        kategoriPengeluran::create(
            [
                'id_users'=>$users->id,
                'nama_ketegori'=>'Uang Makan',
                'deskripsi_kategori'=>'Beli makanan, dan cemilan',
            ]
        );
        }
        
    }
}
