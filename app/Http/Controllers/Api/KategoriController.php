<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriProdukResource;
use Illuminate\Http\Request;
use App\Models\kategoriProduk;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    //index
    public function index()
    {
        return new KategoriProdukResource(true,'Data Found',kategoriProduk::all());
    }

    //show
    public function show($category)
    {
        return new KategoriProdukResource(true,'Data Found',kategoriProduk::find($category));
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_kategori' => 'required',
            'deskripsi'=>'required'
        ]);
         //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $kategori = kategoriProduk::create([
            'nama_kategori'=>$request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return new KategoriProdukResource(true,'Data Stored',$kategori);
    }
}
