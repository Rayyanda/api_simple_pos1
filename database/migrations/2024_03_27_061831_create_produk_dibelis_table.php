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
        Schema::create('produk_dibeli', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_transaksi');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi');
            $table->uuid('produk_id');
            $table->foreign('produk_id')->references('uuid_produk')->on('produk');
            $table->integer('qty');
            $table->double('harga_satuan');
            $table->double( 'discount' )->nullable()->default(0);
            $table->double( 'total' );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_dibeli');
    }
};
