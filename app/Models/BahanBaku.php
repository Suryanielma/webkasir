<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'tgl_pembelian',
        'keterangan',
        'metode_pembayaran',
        'total_pengeluaran',
    ];

    protected $casts = [
        'tgl_pembelian' => 'date',
        'total_pengeluaran' => 'decimal:2',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(DetailBahanBaku::class);
    }

    /**
     * Hitung ulang total dari detail dan simpan.
     */
    public function recalculateTotal(): void
    {
        $this->total_pengeluaran = $this->details()->sum('harga_total');
        $this->save();
    }
}