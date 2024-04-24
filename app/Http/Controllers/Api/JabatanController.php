<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JabatanResource;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    //index
    public function index()
    {
        return new JabatanResource(true, "Data Found",Jabatan::all());
    }
}
