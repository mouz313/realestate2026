<?php

namespace App\Models;

use App\Scopes\AgentScope;
use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use App\Traits\HasNotificationPrefs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use LogsActivity, BelongsToCompany, HasNotificationPrefs, Notifiable;

    protected static function booted(): void
    {
        static::addGlobalScope(new AgentScope('created_by'));
    }

    protected $fillable = [
        'company_id', 'name', 'company', 'email', 'phone', 'address', 'notes', 'password',
        'client_type', 'cnic', 'cnic_verified',
    ];

    protected function casts(): array
    {
        return [
            'cnic_verified' => 'boolean',
        ];
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function ownedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function dealsAsBuyer(): HasMany
    {
        return $this->hasMany(Deal::class, 'buyer_id');
    }

    public function dealsAsSeller(): HasMany
    {
        return $this->hasMany(Deal::class, 'seller_id');
    }

    public function propertyVisits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class);
    }

}
