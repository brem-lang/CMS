<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FailedPayment extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'email',
        'full_name',
        'phone',
        'address',
        'town',
        'state',
        'postcode',
        'country',
        'order_notes',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'payment_intent_id',
        'payment_source_id',
        'checkout_session_id',
        'status',
        'courier_id',
        'items',
        'failed_at',
        'failure_reason',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'failed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function failedPaymentItems(): HasMany
    {
        return $this->hasMany(FailedPaymentItem::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'failed_payment_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }
}
