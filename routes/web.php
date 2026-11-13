<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\barangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\marginController;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\satuanController;


Route::get('/', function () {
    return view('welcome');
});

// Route untuk menampilkan halaman daftar barang
Route::get('/barang', [barangController::class, 'index']);
Route::get('/barang/create', [barangController::class, 'create']);
Route::post('/barang', [barangController::class, 'store']);

Route::get('/user', [UserController::class, 'index']);

Route::get('/role', [RoleController::class, 'index']);

Route::resource('satuan', satuanController::class);

Route::resource('vendor', vendorController::class);

Route::resource('margin', marginController::class);

Route::resource('barang', barangController::class);

Route::resource('satuan', satuanController::class);
