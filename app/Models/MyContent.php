<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MyContent extends Model
{
    protected $fillable = [
        'title',
        'video_path',
        'highlights',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (MyContent $content): void {
            if ($content->video_path && Storage::disk('public')->exists($content->video_path)) {
                Storage::disk('public')->delete($content->video_path);
            }
        });
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        return Storage::url($this->video_path);
    }
}
