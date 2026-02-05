<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'size_options' => 'array',
        'color_options' => 'array',
    ];

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the default image URL from variants (first variant's image)
     */
    public function getImageUrlAttribute(): string
    {
        $firstVariant = $this->variants()->whereNotNull('color_image')->first();
        
        if ($firstVariant && $firstVariant->color_image_url) {
            return $firstVariant->color_image_url;
        }

        return asset('bootstrap/img/product/product-1.jpg'); // fallback image
    }

    /**
     * Get hex color code for a color name
     */
    public static function getColorHex(string $colorName): string
    {
        $colorMap = [
            'red' => '#FF0000',
            'blue' => '#0000FF',
            'green' => '#008000',
            'yellow' => '#FFFF00',
            'black' => '#000000',
            'white' => '#FFFFFF',
            'gray' => '#808080',
            'grey' => '#808080',
            'orange' => '#FFA500',
            'purple' => '#800080',
            'pink' => '#FFC0CB',
            'brown' => '#A52A2A',
            'navy' => '#000080',
            'maroon' => '#800000',
            'teal' => '#008080',
            'cyan' => '#00FFFF',
            'magenta' => '#FF00FF',
            'lime' => '#00FF00',
            'olive' => '#808000',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'beige' => '#F5F5DC',
            'tan' => '#D2B48C',
            'coral' => '#FF7F50',
            'salmon' => '#FA8072',
            'turquoise' => '#40E0D0',
            'violet' => '#EE82EE',
            'indigo' => '#4B0082',
            'khaki' => '#F0E68C',
        ];

        $colorNameLower = strtolower(trim($colorName));
        return $colorMap[$colorNameLower] ?? '#CCCCCC'; // Default gray if color not found
    }
}
