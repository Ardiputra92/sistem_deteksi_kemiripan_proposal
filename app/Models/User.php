<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Similarity; // Tambahkan ini jika belum ada

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'level',
    'email',
    'password',
    'nim',
    'program_studi',
    'kelas',
    'no_hp',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke Similarity (satu user bisa punya banyak similarity)
     */
    public function similarities()
    {
        return $this->hasMany(Similarity::class);
    }
}
