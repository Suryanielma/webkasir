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
        $table->string('nama_kasir')->nullable()->after('id_sesi');
    });
}

public function down(): void
{
    Schema::table('transaksi_penjualan', function (Blueprint $table) {
        $table->dropColumn('nama_kasir');
    });
}
};
