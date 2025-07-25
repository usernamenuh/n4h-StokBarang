<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->enum('import_type', ['transaksi', 'barang']);
            $table->integer('total_rows');
            $table->integer('successful_rows');
            $table->integer('failed_rows');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('error_details')->nullable();
            $table->unsignedBigInteger('imported_by')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
            
            $table->index('import_type');
            $table->index('status');
            $table->index('imported_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_logs');
    }
};
