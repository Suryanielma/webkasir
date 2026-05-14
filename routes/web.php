<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/transaksi', 'transaksi')->name('transaksi');
    Route::view('/menu', 'menu')->name('menu');
    Route::view('/bahan-baku', 'bahan-baku')->name('bahan-baku');
    Route::view('/laporan', 'laporan')->name('laporan');
    
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);
});
