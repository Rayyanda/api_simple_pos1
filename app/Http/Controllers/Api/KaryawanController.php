<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KaryawanResource;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    //index
    public function index()
    {
        return new KaryawanResource(true,'Data Found',Karyawan::all());
    }

    //show
    public function show($uuid_karyawan)
    {
        $karyawan = Karyawan::join('jabatan','karyawan.jabatan_id','=','jabatan.id')
                            ->select('karyawan.*','jabatan.nama as jabatan')
                            ->where('karyawan.id_karyawan' ,$uuid_karyawan)->first();
        return new KaryawanResource(true,'Data Found',$karyawan);
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nik'=> 'required|numeric|min_digits:16|max_digits:16',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|numeric|digits_between:8,13',
            'jabatan_id' => 'required|exists:jabatan,id',
            'jenis_kelamin'=> 'required',
            'email' => 'email',
            'tanggal_lahir' => 'required|date|before:today',
            'profil'=>'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        //upload image
        $profile = $request->file('profil');
        $profile->storeAs('public/emp_profile',$profile->hashName());

        $karyawan = Karyawan::create([
            'id_karyawan' => Str::uuid(),
            'nik' => $request->nik,
            'nama'=>$request->nama,
            'alamat'=>$request->alamat,
            'no_hp'=> $request->no_hp,
            'jabatan_id'=>$request->jabatan_id,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'email'=>$request->email,
            'tanggal_lahir'=> Carbon::parse($request->tanggal_lahir)->format('Y-m-d'),
            'profil'=>$profile->hashName()
        ]);

        return new KaryawanResource(true,"Data Stored",$karyawan);

    }

    //update
    public function update(Request $request,$uuid)
    {
        $validator = Validator::make($request->all(),[
            'nik'=> 'required|numeric|min_digits:16|max_digits:16',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|numeric|digits_between:8,13',
            'jabatan_id' => 'required|exists:jabatan,id',
            'jenis_kelamin'=> 'required',
            'tanggal_lahir' => 'required|date|before:today',
        ]);

         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $karyawan = Karyawan::where('uuid_karyawan','=',$uuid)->first();
        if (is_null($karyawan)) {
            return new KaryawanResource(false,'Not Found',"Karyawan dengan uuid ".$uuid." tidak ditemukan");
        }

        $karyawan->update([
            'nik' => $request->nik,
            'nama'=>$request->nama,
            'alamat'=>$request->alamat,
            'no_hp'=> $request->no_hp,
            'jabatan_id'=>$request->jabatan_id,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'tanggal_lahir'=>Carbon::parse($request->tanggal_lahir)->format('Y-m-d')
        ]) ;
        
        return new KaryawanResource(true,"Updated Data",$karyawan);
    }
}
