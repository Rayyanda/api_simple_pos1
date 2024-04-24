<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * route register
 * @method 'Post'
 */
Route::post('/register',\App\Http\Controllers\Api\RegisterController::class)->name( 'register' );

/**
 * Route "/login"
 * @method "POST"
 */
Route::post("/login", \App\Http\Controllers\Api\LoginController::class)->name("login");

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::middleware(["auth:api","levelCheck:admin,user"])->group(function ()
{
    Route::apiResource('/category',\App\Http\Controllers\Api\KategoriController::class);

    Route::apiResource('/product',\App\Http\Controllers\Api\ProdukController::class);

    Route::get('/product/s/{name}',[\App\Http\Controllers\Api\ProdukController::class,'search'])->name('produk.cari');
    //Route::apiResource('product/detail/{uuid}','\App\Http\Controllers\Api\ProdukController, @detail');

    Route::apiResource('/sales',\App\Http\Controllers\Api\PenjualanController::class);

    Route::apiResource('/purchasedby',\App\Http\Controllers\Api\ProdukDibeliController::class);

    Route::apiResource('/transaction',\App\Http\Controllers\Api\TransaksiController::class);

    Route::apiResource('/account',\App\Http\Controllers\Api\UserController::class);
});

Route::middleware(['auth:api',"levelCheck:admin,hrm"])->group(function() 
{
    //route untuk manajemen karyawan
    Route::apiResource('/employee',\App\Http\Controllers\Api\KaryawanController::class);

    //route untuk jabatan
    route::apiResource('/ps',\App\Http\Controllers\Api\JabatanController::class);
});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});