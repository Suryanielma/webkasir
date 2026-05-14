<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_pembelian');
            $table->string('keterangan');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer', 'QRIS'])->default('Tunai');
            $table->decimal('total_pengeluaran', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};