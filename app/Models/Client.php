<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'address', 'notes', 'password',
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

    public function portalActions(): HasMany
    {
        return $this->hasMany(PortalAction::class);
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

    public function rentAgreementsAsTenant(): HasMany
    {
        return $this->hasMany(RentAgreement::class, 'tenant_id');
    }

    public function rentAgreementsAsOwner(): HasMany
    {
        return $this->hasMany(RentAgreement::class, 'owner_id');
    }

    public function propertyVisits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class);
    }
}
