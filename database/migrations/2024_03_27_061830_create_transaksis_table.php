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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_transaksi')->unique();
            $table->uuid('id_penjualan');
            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan');
            $table->datetime('tgl_transaksi');
            $table->double('total_harga')->default(0)->nullable();
            $table->string('metode_pembayaran');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
