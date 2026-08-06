<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentNotice extends Model
{
    protected $fillable = [
        'rent_agreement_id', 'tenant_id', 'notice_date', 'move_out_date',
        'notice_type', 'reason', 'status', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'notice_date' => 'date',
            'move_out_date' => 'date',
        ];
    }

    public function rentAgreement(): BelongsTo
    {
        return $this->belongsTo(RentAgreement::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'tenant_id');
    }
}
