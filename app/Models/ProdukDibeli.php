<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukDibeli extends Model
{
    use HasFactory;

    protected $table = 'produk_dibeli';

    protected $fillable = [
        'id_transaksi',  
        'produk_id',  
        'qty',  
        'harga_satuan', 
        'discount',
        'total'
    ];


    //store
    public function store(array $data)
    {
        return $this->create([
            'id_transaksi' => $data['id_transaksi'],
            'produk_id' => $data['produk_id'],
            'qty' => $data['jumlah'] ?? 1,
            'harga_satuan' => $data['harga'],
            'total' => $data['subtotal']
        ]);
    }
}
