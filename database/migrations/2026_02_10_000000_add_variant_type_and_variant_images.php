<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('variant_type')->nullable()->after('color_options'); // 'size', 'color', 'both'
        });

        // Default existing products that have variants to 'both'
        DB::table('products')
            ->whereIn('id', DB::table('product_variants')->select('product_id')->distinct())
            ->update(['variant_type' => 'both']);

        Schema::table('product_variants', function (Blueprint $table) {
            $table->json('images')->nullable()->after('color');
        });

        // Migrate color_image to images array
        $variants = DB::table('product_variants')->whereNotNull('color_image')->get();
        foreach ($variants as $variant) {
            DB::table('product_variants')
                ->where('id', $variant->id)
                ->update(['images' => json_encode([$variant->color_image])]);
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('color_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color_image')->nullable()->after('color');
        });

        $variants = DB::table('product_variants')->whereNotNull('images')->get();
        foreach ($variants as $variant) {
            $images = json_decode($variant->images, true);
            $first = is_array($images) ? ($images[0] ?? null) : null;
            if ($first) {
                DB::table('product_variants')->where('id', $variant->id)->update(['color_image' => $first]);
            }
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('images');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('variant_type');
        });
    }
};
