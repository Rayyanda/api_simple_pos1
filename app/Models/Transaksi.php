<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'id_transaksi',
        'id_penjualan', 
        'tgl_transaksi',  
        'total_harga',     
        'metode_pembayaran',
        'keterangan'      
    ];

    //show
    public function show($uuid_transaksi)
    {
        return $this->where('id_transaksi','=',$uuid_transaksi)->first();
    }

    //store
    public function store(array $data)
    {
        return $this->create([
            'id_transaksi'=> Str::uuid(),
            'id_penjualan'=> $data['id_penjualan'],
            'tgl_transaksi'=> date("Y-m-d H:i:s"),
            'total_harga'=> $data['subtotal'],
        ]);
    }
}
