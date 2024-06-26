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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            // $table->string('contact_person');
            $table->string('contact_name');
            $table->char('contact_num');
            $table->string('address');
            $table->date('date_received');
            
            // $table->text('product_name');
            $table->text('product_name_id');

            // $table->string('unit');
            $table->text('unit');

            $table->string('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
