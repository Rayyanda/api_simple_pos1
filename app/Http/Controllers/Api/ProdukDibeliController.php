<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukDibeliResource;
use App\Models\Penjualan;
use App\Models\ProdukDibeli;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProdukDibeliController extends Controller
{
    //index
    public function index()
    {
        $sales_product = ProdukDibeli::all();
        return new ProdukDibeliResource(true,'Data Found',$sales_product);
    }
    
    //show
    public function show($id)
    {
        $sales_product = ProdukDibeli::find($id);
        return new ProdukDibeliResource(true, 'Data Found',$sales_product);
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'produk_id'=>'required|exists:produk,uuid_produk',
            'jumlah'=>'required|numeric',
            'harga_satuan'=>'required|numeric',
            'total_harga'=>'required|numeric',
            'metode_pembayaran'=>'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $m_penjualan = new Penjualan();

        $penjualan = $m_penjualan->NewStore();

        $id_penjualan = $penjualan['id_penjualan'];

        date_default_timezone_set('Asia/Jakarta'); # add your timezone here

        

        $id_transaksi = Str::uuid();

        $transaksi = Transaksi::create([
            'id_transaksi' => $id_transaksi,
            'id_penjualan' => $id_penjualan,
            'tgl_transaksi' => date('Y-m-d'),
            'total_harga' => $request->total_harga,
            'metode_pembayaran'=>$request->metode_pembayaran
        ]);

        $sales_product = ProdukDibeli::create([
            'id_transaksi' => $id_transaksi,
            'produk_id'=>$request->produk_id,
            'qty'=> $request->jumlah,
            'harga_satuan'=>$request->harga_satuan,
            'total'=>$request->total_harga
        ]);

        return new ProdukDibeliResource(true, "Data Stored", $sales_product);

    }
}
