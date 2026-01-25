<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill status history for existing orders that don't have history
        $orderIdsWithHistory = DB::table('order_status_history')->pluck('order_id')->unique();
        $orders = Order::whereNotIn('id', $orderIdsWithHistory)->get();
        
        foreach ($orders as $order) {
            DB::table('order_status_history')->insert([
                'order_id' => $order->id,
                'status' => $order->status ?? 'pending',
                'courier' => $order->courier,
                'notes' => 'Order created',
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need a down method as it's just data migration
    }
};
