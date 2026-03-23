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
            if (! $content->video_path) {
                return;
            }

            // Delete only local files from the public disk.
            if (str_starts_with($content->video_path, 'http://') || str_starts_with($content->video_path, 'https://')) {
                return;
            }

            $normalizedPath = ltrim(str_replace('/storage/', '', $content->video_path), '/');

            if ($normalizedPath !== '' && Storage::disk('public')->exists($normalizedPath)) {
                Storage::disk('public')->delete($normalizedPath);
            }
        });
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        $rawPath = trim($this->video_path);

        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            return $rawPath;
        }

        // Normalize legacy/local paths so all records resolve consistently.
        $normalizedPath = str_replace('\\', '/', $rawPath);

        if (str_starts_with($normalizedPath, '/storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('/storage/'));
        }

        $normalizedPath = ltrim($normalizedPath, '/');

        if ($normalizedPath === '') {
            return null;
        }

        return Storage::url($normalizedPath);
    }
}
