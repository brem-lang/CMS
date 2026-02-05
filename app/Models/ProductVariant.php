<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
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
}
