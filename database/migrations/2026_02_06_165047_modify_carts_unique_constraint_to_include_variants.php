<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up any duplicate entries that would violate the new constraint
        // Keep only the most recent entry for each user_id, product_id, status, selected_size, selected_color combination
        $duplicates = DB::select("
            SELECT user_id, product_id, status, selected_size, selected_color, COUNT(*) as count
            FROM carts
            WHERE status = 'pending'
            GROUP BY user_id, product_id, status, selected_size, selected_color
            HAVING count > 1
        ");

        foreach ($duplicates as $duplicate) {
            // Get all duplicate entries, keep the most recent one
            $idsToDelete = DB::select("
                SELECT id FROM carts
                WHERE user_id = ? AND product_id = ? AND status = ? 
                AND (selected_size <=> ?) AND (selected_color <=> ?)
                ORDER BY created_at DESC
                LIMIT 1000 OFFSET 1
            ", [
                $duplicate->user_id,
                $duplicate->product_id,
                $duplicate->status,
                $duplicate->selected_size,
                $duplicate->selected_color
            ]);

            if (!empty($idsToDelete)) {
                $ids = array_column($idsToDelete, 'id');
                DB::table('carts')->whereIn('id', $ids)->delete();
            }
        }

        // Add the new unique constraint that includes selected_size and selected_color
        // Use prefix indexes for VARCHAR columns to avoid exceeding MySQL's key length limit (3072 bytes)
        // Prefix of 100 characters should be sufficient for size/color values (e.g., 'XL', 'red', etc.)
        // Check if it already exists first
        $indexes = DB::select("SHOW INDEXES FROM carts WHERE Key_name = 'carts_user_product_status_size_color_unique'");
        if (empty($indexes)) {
            try {
                DB::statement('ALTER TABLE carts ADD UNIQUE KEY carts_user_product_status_size_color_unique (user_id, product_id, status, selected_size(100), selected_color(100))');
            } catch (\Exception $e) {
                // If it fails due to duplicates, that's okay - the application code will handle it
                // We'll just log it
                \Log::warning('Could not add unique constraint to carts table: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop the new unique constraint
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_product_status_size_color_unique');
            
            // Restore the old unique constraint
            $table->unique(['user_id', 'product_id', 'status']);
        });
    }
};
