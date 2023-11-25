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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            // $table->char('contact_num');
            $table->string('product_name');
            $table->integer('qty');
            $table->integer('unit_price');
            $table->integer('total_price');
            // $table->integer('amount_tendered');
            // $table->integer('change_due');
            $table->integer('total_earned');
            $table->timestamp('last_transaction_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};