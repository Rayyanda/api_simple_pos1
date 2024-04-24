<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //index
    public function index()
    {
        $user = User::where('level','!=','admin')->get();
        return new UserResource(true, "User Found",$user);
    }

    //show
    public function show($uuid_user)
    {
        $user = User::where('user_uuid','=',$uuid_user)->first();
        if ($user){
            return new UserResource(true,"Detail User Found",$user);
        }else{
            return new UserResource(false,"User Not Found",null);
        }
    }

    //create
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'name' => 'required|string',
            'password' => 'required|min_digits:6',
            'level' => 'required|string'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $user = User::create([
            'user_uuid'=>Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => $request->level
        ]);

        return new UserResource(true,"Create user successfull",$user);
    }
}
