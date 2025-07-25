<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add indexes for better performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('name', 'idx_users_name');
            $table->index('email', 'idx_users_email');
        });
        
        Schema::table('barang', function (Blueprint $table) {
            $table->index('user_id', 'idx_barang_user_id');
            // kode sudah ada index dari unique constraint
        });
        
        // Optimize existing indexes
        Schema::table('barang', function (Blueprint $table) {
            // Add composite index for common queries
            $table->index(['user_id', 'golongan'], 'idx_barang_user_golongan');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_name');
            $table->dropIndex('idx_users_email');
        });
        
        Schema::table('barang', function (Blueprint $table) {
            $table->dropIndex('idx_barang_user_id');
            $table->dropIndex('idx_barang_user_golongan');
        });
    }
};