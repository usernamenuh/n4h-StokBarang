<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor');
            $table->string('customer');
            $table->unsignedBigInteger('barang_id');
            $table->integer('qty');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('disc', 15, 2)->default(0);
            $table->decimal('ongkos', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['tanggal', 'customer']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};