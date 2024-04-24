<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];
}
