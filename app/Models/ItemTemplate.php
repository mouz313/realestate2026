<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'name', 'description', 'unit', 'default_price', 'category', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
