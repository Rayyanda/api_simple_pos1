<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiResource;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    //index
    public function index()
    {
        $transaksi = Transaksi::select('transaksi.id_transaksi','transaksi.tgl_transaksi','transaksi.total_harga', DB::raw('COUNT(produk_dibeli.id) AS total_produk'))
            ->join('produk_dibeli','produk_dibeli.id_transaksi', '=', 'transaksi.id_transaksi')
            ->groupBy('transaksi.id_transaksi','transaksi.tgl_transaksi','transaksi.total_harga')
            ->orderBy('transaksi.tgl_transaksi', 'desc')
            ->get();
        return new TransaksiResource(true,'Data Found',$transaksi);
    }

    //show
    public function show($id_transaksi)
    {
        $transaksi = Transaksi::select('produk.nama_produk','produk_dibeli.qty','produk_dibeli.harga_satuan', 'produk_dibeli.total')
            ->join('produk_dibeli','produk_dibeli.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('produk','produk.uuid_produk','=','produk_dibeli.produk_id')
            ->where('transaksi.id_transaksi','=',$id_transaksi)
            ->orderBy('produk.nama_produk', 'asc')
            ->get();
        return new TransaksiResource(true,'Data Found',$transaksi);
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'tgl_transaksi'=>'required|date',
            'id_penjualan' => 'required',
        ]);

         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = Transaksi::create([
            "tgl_transaksi" => $request->tgl_transaksi,
            'id_penjualan' => $request->id_penjualan
        ]);

        return new TransaksiResource(true,"Data Stored",$transaksi);

    }
}
