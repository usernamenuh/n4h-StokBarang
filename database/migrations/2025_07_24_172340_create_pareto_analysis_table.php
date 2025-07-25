<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pareto_analysis', function (Blueprint $table) {
            $table->id();
            $table->enum('analysis_type', ['customer', 'barang']);
            $table->string('period'); // Format: YYYY-MM
            $table->string('item_id')->nullable();
            $table->string('item_name');
            $table->decimal('total_value', 15, 2);
            $table->integer('total_qty')->default(0);
            $table->decimal('percentage', 5, 2);
            $table->decimal('cumulative_percentage', 5, 2);
            $table->enum('abc_category', ['A', 'B', 'C']);
            $table->integer('rank');
            $table->timestamps();

            $table->index(['analysis_type', 'period']);
            $table->index(['abc_category', 'analysis_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pareto_analysis');
    }
};