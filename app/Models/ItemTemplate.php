<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model
{
    protected $fillable = [
        'name', 'description', 'unit', 'default_price', 'category', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
