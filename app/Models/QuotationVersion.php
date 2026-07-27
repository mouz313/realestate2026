<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationVersion extends Model
{
    protected $fillable = [
        'quotation_id', 'version_number', 'client_id', 'property_id',
        'discount_type', 'discount_value', 'discount_amount',
        'subtotal', 'tax_rate', 'tax_amount', 'total', 'notes', 'items_data',
    ];

    protected function casts(): array
    {
        return [
            'items_data' => 'array',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
}
