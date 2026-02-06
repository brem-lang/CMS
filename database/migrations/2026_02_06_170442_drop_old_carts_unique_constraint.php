<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration:
     * 1. Drops foreign keys that depend on the old unique constraint
     * 2. Drops the old unique constraint (user_id, product_id, status)
     * 3. Adds the new unique constraint (user_id, product_id, status, selected_size, selected_color)
     * 4. Recreates the foreign keys
     */
    public function up(): void
    {
        // Step 1: Clean up any duplicate entries that would violate constraints
        $duplicates = DB::select("
            SELECT user_id, product_id, status, COUNT(*) as count
            FROM carts
            WHERE status = 'pending'
            GROUP BY user_id, product_id, status
            HAVING count > 1
        ");
        
        foreach ($duplicates as $duplicate) {
            // Keep only the most recent entry, delete others
            $idsToDelete = DB::select("
                SELECT id FROM carts
                WHERE user_id = ? AND product_id = ? AND status = ?
                ORDER BY created_at DESC
                LIMIT 1000 OFFSET 1
            ", [
                $duplicate->user_id,
                $duplicate->product_id,
                $duplicate->status
            ]);
            
            if (!empty($idsToDelete)) {
                $ids = array_column($idsToDelete, 'id');
                DB::table('carts')->whereIn('id', $ids)->delete();
            }
        }
        
        // Step 2: Drop foreign keys (they depend on the unique index)
        // Get foreign key constraint names
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'carts' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE carts DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
                // Continue if foreign key doesn't exist
            }
        }
        
        // Step 3: Drop the old unique constraint
        try {
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_id_product_id_status_unique');
        } catch (\Exception $e) {
            // Continue if index doesn't exist
        }
        
        // Step 4: Add the new unique constraint that includes selected_size and selected_color
        // Use prefix indexes for VARCHAR columns to avoid exceeding MySQL's key length limit (3072 bytes)
        // Prefix of 100 characters should be sufficient for size/color values (e.g., 'XL', 'red', etc.)
        // Check if it already exists first
        $newIndexes = DB::select("SHOW INDEXES FROM carts WHERE Key_name = 'carts_user_product_status_size_color_unique'");
        if (empty($newIndexes)) {
            DB::statement('ALTER TABLE carts ADD UNIQUE KEY carts_user_product_status_size_color_unique (user_id, product_id, status, selected_size(100), selected_color(100))');
        }
        
        // Step 5: Recreate foreign keys
        try {
            DB::statement('ALTER TABLE carts ADD CONSTRAINT carts_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
        
        try {
            DB::statement('ALTER TABLE carts ADD CONSTRAINT carts_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new unique constraint
        try {
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_product_status_size_color_unique');
        } catch (\Exception $e) {
            // Continue if doesn't exist
        }
        
        // Drop foreign keys
        try {
            DB::statement('ALTER TABLE carts DROP FOREIGN KEY carts_user_id_foreign');
        } catch (\Exception $e) {
            // Continue if doesn't exist
        }
        
        try {
            DB::statement('ALTER TABLE carts DROP FOREIGN KEY carts_product_id_foreign');
        } catch (\Exception $e) {
            // Continue if doesn't exist
        }
        
        // Restore the old unique constraint
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'status'], 'carts_user_id_product_id_status_unique');
        });
        
        // Recreate foreign keys
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
