<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'harga',
        'gambar',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}