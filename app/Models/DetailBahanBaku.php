<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBahanBaku extends Model
{
    protected $table = 'detail_bahan_baku';

    protected $fillable = [
        'bahan_baku_id',
        'nama_bahan',
        'qty',
        'satuan',
        'harga_total',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'harga_total' => 'decimal:2',
    ];

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }
}