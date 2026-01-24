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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('email');
            $table->string('full_name');
            $table->string('phone');
            $table->text('address');
            $table->string('town');
            $table->string('state');
            $table->string('postcode');
            $table->string('country')->default('Philippines');
            $table->text('order_notes')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_method'); // gcash, paymaya, bank_transfer
            $table->string('payment_status')->default('pending'); // pending, paid, failed, cancelled
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_source_id')->nullable();
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->json('items'); // Store cart items as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
