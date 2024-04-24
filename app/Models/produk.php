<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    // $table->uuid('uuid_produk')->unique();
    //         $table->string('nama_prpduk');
    //         $table->text('deskripsi')->nullable();
    //         $table->unsignedBigInteger('kategori_id');
    //         $table->foreign('kategori_id')
    //             ->references('id')
    //             ->on('kategori_produk');
    //         $table->double('harga');
    //         $table->string('gambar')->nullable();
    protected $fillable = [
        'uuid_produk',
        'nama_produk',
        'deskripsi',
        'kategori_id',
        'harga',
        'gambar',
        'stok'
    ];

}
