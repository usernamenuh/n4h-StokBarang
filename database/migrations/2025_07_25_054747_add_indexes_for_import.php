<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('name'); // For user lookup
        });
        
        Schema::table('barang', function (Blueprint $table) {
            $table->index('user_id'); // For foreign key performance
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
        
        Schema::table('barang', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};