<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'file_dokumen',
        'n_gram',
        'hashing',
        'winnowing',
        'fingerprint',
        'total_fingerprint', // Kolom baru
        'total_ngram', // Kolom baru
        'total_hash', // Kolom baru
        'total_window', // Kolom baru
    ];
}
