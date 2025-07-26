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
        Schema::create('pareto_analises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('nama_barang');
            $table->decimal('total_qty', 15, 2)->default(0);
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->decimal('persentase', 6, 2)->default(0);
            $table->enum('kategori', ['A', 'B', 'C'])->nullable();
            $table->decimal('stok_saat_ini', 15, 2)->default(0);
            $table->string('periode', 20)->nullable(); // contoh: 2025-07 untuk analisis bulanan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pareto_analises');
    }
};
