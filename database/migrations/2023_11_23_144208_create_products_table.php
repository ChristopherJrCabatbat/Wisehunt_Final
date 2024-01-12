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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('brand_name');
            $table->string('description');
            $table->string('category');
            $table->string('photo')->nullable();
            $table->integer('quantity');
            $table->integer('low_quantity_threshold')->default(0); // New column
            // $table->bigInteger('capital');
            $table->bigInteger('purchase_price');
            // $table->bigInteger('unit_price');
            $table->bigInteger('selling_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
