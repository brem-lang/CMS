<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DigitalProduct extends Model
{
    protected $guarded = [];

    protected $casts = [

        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'for_subscribers' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saved(function (DigitalProduct $product) {
            if ($product->for_subscribers) {
                self::where('id', '!=', $product->id)->update(['for_subscribers' => false]);
            }
        });
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return null;
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }

        return null;
    }
}
