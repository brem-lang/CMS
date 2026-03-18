<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['ip_address', 'url', 'visited_at'];

    protected function casts(): array
    {
        return [
            'visited_at' => 'date',
        ];
    }
}
