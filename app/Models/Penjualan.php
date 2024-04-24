<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Penjualan extends Model
{
    use HasFactory;

    protected $table=  'penjualan';

    protected $fillable = [
        'id_penjualan',
        'tgl_transaksi',
        'total',
        'keterangan'
    ];

    public function index()
    {
        return $this->all();
    }

    public function show($uuid_penjualan)
    {
        return $this->where('id_penjualan','=',$uuid_penjualan)->first();
    }

    public function NewStore()
    {
        date_default_timezone_set('Asia/Jakarta'); # add your timezone here

        return $this->create([
            'id_penjualan'=> Str::uuid(),
            'tgl_transaksi'=> date("Y-m-d H:i:s"),
        ]);
    }

}
