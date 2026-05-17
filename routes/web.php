<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\SesiKasirController; 
use App\Http\Controllers\TransaksiKasirController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [TransaksiKasirController::class, 'index'])->name('transaksi')->middleware('role:Kasir');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');

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

    Route::get('/sesi-kasir', [SesiKasirController::class, 'index'])->name('sesi-kasir');
    Route::get('/buku-kasir', [SesiKasirController::class, 'bukuKasir'])->name('buku-kasir');
    Route::get('/buku-kasir/{id_sesi}', [SesiKasirController::class, 'detailBukuKasir'])->name('buku-kasir.detail');
    Route::post('/sesi-kasir/buka', [SesiKasirController::class, 'bukaKasir'])->name('sesi-kasir.buka');
    Route::post('/sesi-kasir/tutup', [SesiKasirController::class, 'tutupKasir'])->name('sesi-kasir.tutup');
    Route::post('/transaksi/checkout', [TransaksiKasirController::class, 'store'])->name('transaksi.checkout');
    Route::get('/transaksi/{id_transaksi}/struk', [TransaksiKasirController::class, 'struk'])->name('transaksi.struk');
    Route::get('/transaksi/{id_transaksi}', [TransaksiKasirController::class, 'detail'])->name('transaksi.detail');
});
