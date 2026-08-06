<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalAction extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'quotation_id', 'client_id', 'action', 'notes',
        'ip_address', 'user_agent', 'signed_name',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
