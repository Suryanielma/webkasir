<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->string('tipe_pesanan')->nullable();
            $table->string('nama_pembeli')->nullable();
            $table->string('nomor_meja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropColumn(['tipe_pesanan', 'nama_pembeli', 'nomor_meja']);
        });
    }
};
