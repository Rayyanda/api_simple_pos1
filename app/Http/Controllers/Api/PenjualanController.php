<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PenjualanResource;
use App\Models\Penjualan;
use App\Models\produk;
use App\Models\ProdukDibeli;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    //index
    public function index() {

        $penjualan = Penjualan::select('penjualan.tgl_transaksi','penjualan.id_penjualan','total','penjualan.updated_at', DB::raw('COUNT(transaksi.id_transaksi) AS total_transaksi'))
            ->join('transaksi', 'penjualan.id_penjualan', '=', 'transaksi.id_penjualan')
            ->groupBy('penjualan.id_penjualan','penjualan.tgl_transaksi','penjualan.total','penjualan.updated_at')
            ->orderBy('penjualan.tgl_transaksi', 'desc')
            ->get();
        return new PenjualanResource(true,'Data Found',$penjualan);
    }

    //show
    public function show($id)
    {
        $penjualan = Penjualan::select('transaksi.*')
            ->join('transaksi', 'penjualan.id_penjualan', '=', 'transaksi.id_penjualan')
            
            ->where('penjualan.id_penjualan','=',$id)
            ->get();
        return new PenjualanResource(true,'Data Found',$penjualan);
    }

    //store
    public function store(Request $request)
    {
        
        foreach ($request->all() as $item ) {
            # code...
            $validator = Validator::make($item,[
                'produk_id'=>'required|exists:produk,uuid_produk',
                'jumlah'=>'required|numeric',
                'harga_satuan'=>'required|numeric',
                'total_harga'=>'required|numeric',
                'metode_pembayaran'=>'required'
            ]);
        };


        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        date_default_timezone_set('Asia/Jakarta');

        $belanja = 0;
        //mengambil total belanja
        foreach ($request->all() as $key) {
            # code...
            $belanja +=  $key['total_harga'];
        }
        
        //cek apakah tgl penjualan sudah ada
        $cekTanggalPenjualan = Penjualan::whereDate('tgl_transaksi',date('Y-m-d'))->first();
        if ($cekTanggalPenjualan){
            $id_penjualan = $cekTanggalPenjualan->id_penjualan;
            $cekTanggalPenjualan->update([
                'total' =>  $cekTanggalPenjualan->total + $belanja
            ]);
        }else{
            $id_penjualan = Str::uuid();
            $penjualan = Penjualan::create([
                'id_penjualan' => $id_penjualan,
                'tgl_transaksi' => date('Y-m-d'),
                'total' => $cekTanggalPenjualan ? $cekTanggalPenjualan->total+$belanja :$belanja ,
            ]);
            
        }

        $id_transaksi = Str::uuid();

        $transaksi = Transaksi::create([
            'id_transaksi'=> $id_transaksi,
            'id_penjualan' => $id_penjualan,
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'total_harga' =>  $belanja,
            'metode_pembayaran' => "Cash"
        ]);

        foreach ($request->all() as $sale) {
            # code...
            ProdukDibeli::create([
                'id_transaksi' => $id_transaksi,
                'produk_id'=>$sale['produk_id'],
                'qty'=> $sale['jumlah'] ??1,
                'harga_satuan'=>$sale['harga_satuan'],
                'total'=>$sale['total_harga']
                
            ]);
        }

        foreach ($request->all() as $key) {
            # code...
            $produk = produk::where('uuid_produk',$key['produk_id'])->first();
            $stokLama = $produk->stok;
            $stokBaru = $stokLama - $key['jumlah'];
            $produk->update(['stok'=>$stokBaru]);
        }
   
        return new PenjualanResource(true,'Data Stored',null);
    }
}
