<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Similarity extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'nama_file',
        'persentase_kemiripan',
        'user_id', // Tambahkan ini kalau kamu simpan user_id di database
    ];

    protected $casts = [
        'persentase_kemiripan' => 'float',
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
