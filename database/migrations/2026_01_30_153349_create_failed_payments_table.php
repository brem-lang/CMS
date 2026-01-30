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
        Schema::create('failed_payments', function (Blueprint $table) {
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
            $table->string('payment_status')->default('failed'); // failed
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_source_id')->nullable();
            $table->string('checkout_session_id')->nullable();
            $table->string('status')->default('cancelled'); // cancelled
            $table->foreignId('courier_id')->nullable()->constrained()->onDelete('set null');
            $table->json('items'); // Store cart items as JSON
            $table->timestamp('failed_at')->nullable(); // Track when payment failed
            $table->text('failure_reason')->nullable(); // Store PayMongo failure message
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_payments');
    }
};
