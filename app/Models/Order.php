<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        // Track status changes when order is updated
        static::updating(function ($order) {
            if ($order->isDirty('status') || $order->isDirty('courier')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;
                $oldCourier = $order->getOriginal('courier');
                $newCourier = $order->courier;
                
                $notes = [];
                if ($oldStatus !== $newStatus) {
                    $notes[] = "Status changed from {$oldStatus} to {$newStatus}";
                }
                if ($oldCourier !== $newCourier) {
                    if ($newCourier) {
                        $notes[] = "Courier set to {$newCourier}";
                    } else {
                        $notes[] = "Courier removed";
                    }
                }
                
                // Create status history entry
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $newStatus,
                    'courier' => $newCourier,
                    'notes' => !empty($notes) ? implode('. ', $notes) : null,
                ]);
            }
        });
        
        // Create initial status history when order is created
        static::created(function ($order) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $order->status ?? 'pending',
                'courier' => null,
                'notes' => 'Order created',
            ]);
        });
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'asc');
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }
    
    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }
}
