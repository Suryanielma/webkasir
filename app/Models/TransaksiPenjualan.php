<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
    'id_sesi', 'waktu_transaksi', 'total_harga', 'bayar', 
    'kembalian', 'tipe_pesanan', 'nama_pembeli', 'nomor_meja', 'nama_kasir'
];
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function sesiKasir()
    {
        return $this->belongsTo(SesiKasir::class, 'id_sesi', 'id_sesi');
    }
}
