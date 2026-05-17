<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiKasir extends Model
{
    protected $table = 'sesi_kasir';
    protected $primaryKey = 'id_sesi';
    // Hanya menyisakan kolom waktu buka dan tutup
    protected $fillable = ['id_user', 'waktu_buka', 'waktu_tutup'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'id_sesi', 'id_sesi');
    }
}
