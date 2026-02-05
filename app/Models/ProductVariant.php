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
        'color_image',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
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
     * Get the color image URL
     */
    public function getColorImageUrlAttribute(): ?string
    {
        if ($this->color_image) {
            return Storage::disk('public')->url($this->color_image);
        }

        return null;
    }
}
