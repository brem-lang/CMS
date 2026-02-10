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
        'additional_images' => 'array',
        'variant_type' => 'string',
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
     * Get the default image URL
     * Priority: Product image (if no variants) > Variant image > Fallback
     */
    public function getImageUrlAttribute(): string
    {
        // If product has variants, use variant image
        $hasVariants = $this->variants()->exists();
        
        if ($hasVariants) {
            $firstVariant = $this->variants()->get()->first(fn ($v) => ! empty($v->images));
            if ($firstVariant && $firstVariant->color_image_url) {
                return $firstVariant->color_image_url;
            }
        } else {
            // If no variants, use product image
            if ($this->image) {
                return Storage::disk('public')->url($this->image);
            }
        }

        return asset('bootstrap/img/product/product-1.jpg'); // fallback image
    }

    /**
     * Get the product image URL (for products without variants)
     */
    public function getProductImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }

        return null;
    }

    /**
     * Get additional images URLs (for products without variants)
     */
    public function getAdditionalImagesUrlsAttribute(): array
    {
        if (!$this->additional_images || !is_array($this->additional_images)) {
            return [];
        }

        return array_map(function ($image) {
            return Storage::disk('public')->url($image);
        }, array_filter($this->additional_images));
    }

    /**
     * Get the variant image URL for cart/order display (matches selected size/color; supports size-only, color-only, both)
     */
    public function getVariantImageUrlForSelection(?string $selectedSize, ?string $selectedColor): ?string
    {
        if (! $this->variants()->exists()) {
            return null;
        }
        $q = $this->variants()->whereNotNull('images');
        if ($selectedSize !== null && $selectedSize !== '') {
            $q->where('size', $selectedSize);
        }
        if ($selectedColor !== null && $selectedColor !== '') {
            $q->where('color', $selectedColor);
        }
        $variant = $q->first();
        return $variant ? $variant->color_image_url : null;
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
