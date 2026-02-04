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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_size_options')->default(false)->nullable()->after('additional_images');
            $table->boolean('has_color_options')->default(false)->nullable()->after('has_size_options');
            $table->json('size_options')->nullable()->after('has_color_options');
            $table->json('color_options')->nullable()->after('size_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_size_options', 'has_color_options', 'size_options', 'color_options']);
        });
    }
};
