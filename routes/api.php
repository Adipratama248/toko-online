<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\registerController;
Route::post('/register', [registerController::class, 'register']);
Route::get('/showRegister', [registerController::class, 'showRegistration']);

use App\Http\Controllers\BarangController;
Route::middleware('auth:sanctum')->group(function(){
Route::post('/addbarang',[BarangController::class, 'createB']);
Route::put('/editbarang/{id}',[BarangController::class, 'updateB']);
Route::delete('/delbarang/{id}',[BarangController::class, 'deleteB']);
Route::get('/showbarang/{id}',[BarangController::class, 'show']);
Route::get('/showbarang',[BarangController::class, 'showall']);    
});

use App\Http\Controllers\TransaksiController;
Route::middleware('auth:sanctum')->group(function(){
Route::post('/transaksi', [TransaksiController::class, 'createT']);
Route::put('/edittransaksi/{id}', [TransaksiController::class, 'editT']);
Route::delete('/deltransaksi/{id}', [TransaksiController::class, 'deleteT']);
Route::get('/showtransaksi/{id}', [TransaksiController::class, 'getByIdT']);
Route::get('/showtransaksi', [TransaksiController::class, 'getALLT']);
});

use App\Http\Controllers\PembayaranController;
Route::middleware('auth:sanctum')->group(function(){
Route::post('/pembayaran', [PembayaranController::class, 'addP']);
Route::get('/pembayaran', [PembayaranController::class, 'getAllP']);
Route::get('/pembayaran/{id}', [PembayaranController::class, 'getByIdP']);
Route::delete('/pembayaran/{id}', [PembayaranController::class, 'deleteP']);
});

use App\Http\Controllers\LoginController;
Route::post('/login', [LoginController::class, 'login']);
    


// routes/api.php
Route::get('/test', function () {
    return response()->json(['message' => 'route works']);
});
