<?php

namespace App\Services;

use App\Models\CallLog;
use App\Models\Property;

class PropertyMatcher
{
    /**
     * Map a lead's transaction intent to the way properties are listed.
     *
     * A buyer/seller lead looks for a property listed as 'sale', while the
     * call-log taxonomy itself uses 'buy'/'sale'. Previously the property
     * lookup filtered on the raw lead type, so 'buy' leads found nothing.
     */
    public static function propertyType(?string $leadType): ?string
    {
        return match ($leadType) {
            'buy', 'sale' => 'sale',
            'rent' => 'rent',
            'installment' => 'installment',
            default => null,
        };
    }

    /**
     * Build a query for available properties matching the given filters.
     * An empty filter key is simply ignored, so callers may pass partial
     * criteria (e.g. the AJAX matcher only requires city + category).
     */
    public static function buildQuery(array $filters)
    {
        $q = Property::available();

        if (! empty($filters['category'])) {
            $q->where('category', $filters['category']);
        }
        if (! empty($filters['transaction_type'])) {
            $q->where('transaction_type', $filters['transaction_type']);
        }
        if (! empty($filters['city_id'])) {
            $q->where('city_id', $filters['city_id']);
        } elseif (! empty($filters['city'])) {
            $q->where('city', $filters['city']);
        }
        if (! empty($filters['bedrooms'])) {
            $q->where('bedrooms', '>=', $filters['bedrooms']);
        }
        if (! empty($filters['budget_min'])) {
            $q->where('price', '>=', $filters['budget_min']);
        }
        if (! empty($filters['budget_max'])) {
            $q->where('price', '<=', $filters['budget_max']);
        }

        return $q;
    }

    /**
     * Find available properties matching a buyer/renter lead.
     */
    public static function forLead(CallLog $lead, int $limit = 20)
    {
        return self::buildQuery([
            'category' => $lead->category,
            'transaction_type' => self::propertyType($lead->transaction_type),
            'city_id' => $lead->city_id,
            'city' => $lead->city,
            'bedrooms' => $lead->bedrooms,
            'budget_min' => $lead->budget_min,
            'budget_max' => $lead->budget_max,
        ])->take($limit)->get();
    }
}
