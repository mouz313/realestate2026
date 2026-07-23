<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'description', 'default_price', 'unit', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
        ];
    }
}
