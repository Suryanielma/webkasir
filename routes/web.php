<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BukuKasirController; 
use App\Http\Controllers\TransaksiKasirController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/transaksi', [TransaksiKasirController::class, 'index'])->name('transaksi')->middleware('role:Kasir');
    Route::view('/laporan', 'laporan')->name('laporan');

    // Menu — load kategori & produk, support filter
    Route::get('/menu', function () {
        $kategoris = \App\Models\Kategori::all();

        $query = \App\Models\Produk::with('kategori');
        if (request('kategori')) $query->where('id_kategori', request('kategori'));
        if (request('status'))   $query->where('status', request('status'));
        if (request('search'))   $query->where('nama_produk', 'like', '%' . request('search') . '%');
        $semua_produk = $query->get();

        return view('menu', compact('kategoris', 'semua_produk'));
    })->name('menu');
 
    Route::resource('bahan-baku', BahanBakuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);
    Route::patch('produk/{id}/toggle-status', [ProdukController::class, 'toggleStatus'])->name('produk.toggleStatus');

    Route::get('/buku-kasir', [BukuKasirController::class, 'index'])->name('buku-kasir');
});