<?php

use GuzzleHttp\Promise\Create;
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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('harga');
            $table->integer('stok');
            $table->string('foto');
            $table->string('deskripsi');
        });

        Schema::create('transaksi', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('id_barang')->index();
            $table->foreign('id_barang')
            ->references('id')
            ->on('barang')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('jumlah_barang');
            $table->integer('harga_total');
            $table->enum('status',['belum bayar','lunas']);
            $table->timestamp('tgl_transaksi')->useCurrent();
        });

        Schema::create('pembayaran', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('id_transaksi')->index();
            $table->foreign('id_transaksi')
            ->references('id')
            ->on('transaksi')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->timestamp('tgl_pembayaran')->useCurrent();
            $table->string('status');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
        Schema::dropIfExists('penjualaran');
    }
};
