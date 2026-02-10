<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'images',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'images' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant name (e.g., "Black Medium")
     */
    public function getNameAttribute(): string
    {
        $parts = array_filter([$this->color, $this->size]);
        return implode(' ', $parts) ?: 'Default';
    }

    /**
     * Get the primary (first) variant image URL
     */
    public function getColorImageUrlAttribute(): ?string
    {
        $first = $this->getFirstImagePath();
        return $first ? Storage::disk('public')->url($first) : null;
    }

    /**
     * Get the first image path from the images array
     */
    public function getFirstImagePath(): ?string
    {
        $images = $this->images;
        if (! is_array($images) || empty($images)) {
            return null;
        }
        return $images[array_key_first($images)] ?? null;
    }

    /**
     * Get all variant image URLs
     */
    public function getImagesUrlsAttribute(): array
    {
        $images = $this->images;
        if (! is_array($images) || empty($images)) {
            return [];
        }
        return array_values(array_map(
            fn ($path) => Storage::disk('public')->url($path),
            array_filter($images)
        ));
    }
}
