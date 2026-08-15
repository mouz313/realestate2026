<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GatewayPayment extends Model
{
    use BelongsToCompany;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'company_id', 'gateway', 'recipient_type', 'recipient_id', 'currency',
        'amount', 'charged_amount', 'order_id', 'reference', 'status',
        'payload', 'paid_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'charged_amount' => 'decimal:2',
            'payload' => 'json',
            'paid_at' => 'datetime',
        ];
    }

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_FAILED, self::STATUS_EXPIRED];
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}