<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'profile',
        'email',
        'password',
        'email_verified_at',
        'jenis_kelamin',
        'hak_akses',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function KategoriPemasukan(){
        return $this->hasMany(kategoriPemasukan::class);
    }
    public function KategoriPengeluaran(){
        return $this->hasMany(kategoriPengeluran::class);
    }
    public function Pemasukan(){
        return $this->hasMany(Pemasukan::class);
    }
    public function Pengeluaran(){
        return $this->hasMany(Pengeluaran::class);
    }
    public function Tabungan(){
        return $this->hasMany(Tabungan::class);
    }
}
