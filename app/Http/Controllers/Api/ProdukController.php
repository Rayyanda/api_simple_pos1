<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    //index
    public function index()
    {
        return new ProdukResource(true,'Data Found',produk::all());
    }

    //show
    public function show($uuid_produk)
    {
        return new ProdukResource(true, 'Data Found',Produk::where('uuid_produk','=',$uuid_produk)->first());
    }
    
    //search
    public function search($nama_produk)
    {
        return new ProdukResource(true,'Data Found',produk::where('nama_produk','like',"%".$nama_produk."%")->get());
    }

    //detail with uuid
    public function detail($uuid_produk)
    {
        $data = produk::where('uuid',$uuid_produk)->first();
        if ($data){
            return new ProdukResource(false,"Detail Data",$data);
        }else{
            return new ProdukResource(true,"Not Found",null);
        }
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_produk' => 'required',
            'deskripsi'=>'required',
            'kategori_id'=>'required',
            'harga'=>'required|numeric',
            'stok' =>'required|numeric'
        ]);
         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $produk = produk::create([
            'uuid_produk'=>Str::uuid(),
            'nama_produk'=>$request->nama_produk,
            'deskripsi'=>$request->deskripsi,
            'kategori_id'=>$request->kategori_id,
            'harga'=>$request->harga,
            'stok'=>$request->stok
        ]);
        return new ProdukResource(true, "Created Successfully", $produk);
    }

    //update
    public function update(Request $request,$uuidProduk)
    {
        $validator = Validator::make($request->all(),[
            'nama_produk' => 'required',
            'deskripsi'=>'required',
            'kategori_id'=>'required',
            'harga'=>'required|numeric',
            'stok' =>'required|numeric'
        ]);
        
         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $produk = produk::where('uuid_produk','=',$uuidProduk)->update([
            'nama_produk'=>$request->nama_produk,
            'deskripsi'=>$request->deskripsi,
            'kategori_id'=>$request->kategori_id,
            'harga'=>$request->harga,
            'stok'=>$request->stok
        ]);

        return new ProdukResource(true,'Data Updated',$produk);
    }

    //destroy
    public function destroy($uuidProduk){
        $produk = produk::where('uuid_produk',$uuidProduk)->first();
        if(!empty($produk)){
            $produk->delete();
            return new ProdukResource(true,"Deleted Successfully",null);
        }else{
            return new ProdukResource(false,"Not Found",null);
        }
    }
}
