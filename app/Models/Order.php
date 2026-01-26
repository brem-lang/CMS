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
            if ($order->isDirty('status') || $order->isDirty('courier_id')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;
                $oldCourierId = $order->getOriginal('courier_id');
                $newCourierId = $order->courier_id;
                
                $notes = [];
                if ($oldStatus !== $newStatus) {
                    $notes[] = "Status changed from {$oldStatus} to {$newStatus}";
                }
                if ($oldCourierId !== $newCourierId) {
                    if ($newCourierId) {
                        $courierName = Courier::find($newCourierId)?->name ?? 'Unknown';
                        $notes[] = "Courier set to {$courierName}";
                    } else {
                        $notes[] = "Courier removed";
                    }
                }
                
                // Create status history entry
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $newStatus,
                    'courier' => $order->courier?->name,
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
    
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
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
