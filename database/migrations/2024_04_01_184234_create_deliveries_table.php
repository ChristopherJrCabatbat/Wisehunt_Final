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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_id');

            // $table->string('name');
            $table->string('customer_name_id');

            // $table->string('product');
            // $table->integer('quantity');

            $table->text('product'); // Change the data type to TEXT
            $table->text('quantity'); // Change the data type to TEXT

            $table->string('mode_of_payment');
            // $table->string('address');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
