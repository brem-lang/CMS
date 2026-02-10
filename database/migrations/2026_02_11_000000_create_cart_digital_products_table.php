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
        Schema::create('cart_digital_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('digital_product_id')->constrained('digital_products')->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'digital_product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_digital_products');
    }
};
