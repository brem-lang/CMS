<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'additional_images' => 'array',
    ];

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('bootstrap/img/product/product-1.jpg'); // fallback image
    }

    public function getAdditionalImagesUrlsAttribute(): array
    {
        if (empty($this->additional_images) || !is_array($this->additional_images)) {
            return [];
        }

        return array_map(function ($image) {
            return Storage::disk('public')->url($image);
        }, $this->additional_images);
    }
}
