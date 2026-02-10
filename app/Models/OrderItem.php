<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function digitalProduct(): BelongsTo
    {
        return $this->belongsTo(DigitalProduct::class);
    }

    /**
     * Display name for infolist/UI: product name or digital product title with (Digital).
     */
    public function getProductOrDigitalNameAttribute(): string
    {
        if ($this->digital_product_id && $this->digitalProduct) {
            return $this->digitalProduct->title . ' (Digital)';
        }
        if ($this->product_id && $this->product) {
            return $this->product->name;
        }

        return 'Product not found';
    }
}
