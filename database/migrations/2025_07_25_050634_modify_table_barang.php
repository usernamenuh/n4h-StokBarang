<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            // Remove unique constraint from kode
            $table->dropUnique(['kode']);
            // Add regular index instead
            $table->index('kode');
        });
    }

    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropIndex(['kode']);
            $table->unique('kode');
        });
    }
};