<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    protected $table = 'pembelian_bahan';
    protected $primaryKey = 'id_pembelian';
    protected $fillable = ['id_user', 'nama_bahan', 'jumlah', 'harga_total', 'tanggal_pembelian'];
}
