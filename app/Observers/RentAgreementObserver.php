<?php

namespace App\Observers;

use App\Models\Property;
use App\Models\RentAgreement;

class RentAgreementObserver
{
    public function created(RentAgreement $agreement): void
    {
        if ($agreement->status === 'active' && $agreement->property_id) {
            Property::where('id', $agreement->property_id)->update(['status' => 'rented']);
        }
    }

    public function updated(RentAgreement $agreement): void
    {
        if (! $agreement->property_id) {
            return;
        }

        if ($agreement->isDirty('status') && in_array($agreement->getOriginal('status'), ['active', 'pending'])) {
            $this->revertIfNeeded($agreement->property_id);
        }

        if ($agreement->isDirty('status') && $agreement->status === 'active') {
            Property::where('id', $agreement->property_id)->update(['status' => 'rented']);
        }
    }

    public function deleted(RentAgreement $agreement): void
    {
        if ($agreement->property_id) {
            $this->revertIfNeeded($agreement->property_id);
        }
    }

    private function revertIfNeeded(int $propertyId): void
    {
        $hasActive = RentAgreement::where('property_id', $propertyId)
            ->where('status', 'active')
            ->exists();

        if (! $hasActive) {
            Property::where('id', $propertyId)->update(['status' => 'available']);
        }
    }
}
