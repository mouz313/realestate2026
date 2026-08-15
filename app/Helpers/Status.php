<?php

namespace App\Helpers;

class Status
{
    protected static array $maps = [
        'property' => [
            'available' => 'status-available',
            'sold' => 'status-sold',
            'rented' => 'status-rented',
            'under_offer' => 'status-under_offer',
            'pending' => 'status-pending',
            'reserved' => 'status-reserved',
        ],
        'deal' => [
            'inquiry' => 'status-inquiry',
            'visit_scheduled' => 'status-visit_scheduled',
            'offer_made' => 'status-offer_made',
            'token_received' => 'status-token_received',
            'agreement_signed' => 'status-agreement_signed',
            'in_progress' => 'status-in_progress',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
        ],
        'deal_status' => [
            'pending' => 'status-pending',
            'active' => 'status-active',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
        ],
        'installment' => [
            'pending' => 'status-pending',
            'paid' => 'status-paid',
            'overdue' => 'status-cancelled',
            'partial' => 'status-partial',
        ],
        'invoice' => [
            'draft' => 'status-draft',
            'sent' => 'status-sent',
            'approved' => 'status-approved',
            'rejected' => 'status-rejected',
            'invoiced' => 'status-invoiced',
        ],
        'team' => [
            'active' => 'status-active',
            'inactive' => 'status-draft',
            'suspended' => 'status-cancelled',
        ],
        'token' => [
            'received' => 'status-paid',
            'pending' => 'status-pending',
            'cancelled' => 'status-cancelled',
        ],
        'rent_agreement' => [
            'active' => 'status-active',
            'expired' => 'status-draft',
            'terminated' => 'status-cancelled',
            'pending' => 'status-pending',
        ],
        'quotation' => [
            'draft' => 'status-draft',
            'sent' => 'status-sent',
            'approved' => 'status-approved',
            'rejected' => 'status-rejected',
            'invoiced' => 'status-invoiced',
        ],
        'property_visit' => [
            'scheduled' => 'status-active',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
            'rescheduled' => 'status-pending',
            'no_show' => 'status-draft',
        ],
        'commission' => [
            'pending' => 'status-pending',
            'approved' => 'status-active',
            'paid' => 'status-paid',
            'cancelled' => 'status-cancelled',
        ],
        'city' => [
            1 => 'status-active',
            0 => 'status-draft',
        ],
        'portal_quotation' => [
            'draft' => 'secondary',
            'sent' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
            'invoiced' => 'info',
        ],
        'lead_source' => [
            'walk_in' => 'Walk-in',
            'olx' => 'OLX',
            'zameen' => 'Zameen.com',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'website' => 'Website',
            'referral' => 'Referral',
            'other' => 'Other',
        ],
        'property_type' => [
            'house' => 'House',
            'flat' => 'Flat',
            'farmhouse' => 'Farmhouse',
            'plot' => 'Plot',
            'building' => 'Building',
        ],
        'purpose' => [
            'buy' => 'Buy',
            'rent' => 'Rent',
        ],
    ];

    public static function classes(string $group): array
    {
        return self::$maps[$group] ?? [];
    }

    public static function badge(string $group, $status, ?string $label = null, string $default = 'status-pending'): string
    {
        $map = self::$maps[$group] ?? [];
        $class = $map[$status] ?? $default;

        return '<span class="badge '.$class.'">'.e($label ?? ucfirst(str_replace('_', ' ', $status))).'</span>';
    }

    public static function leadSources(): array
    {
        return self::$maps['lead_source'] ?? [];
    }

    public static function leadSourceLabel($key): string
    {
        return self::$maps['lead_source'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
    }

    public static function enquiryPropertyTypes(): array
    {
        return self::$maps['property_type'] ?? [];
    }

    public static function propertyTypeLabel($key): string
    {
        return self::$maps['property_type'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
    }

    public static function purposes(): array
    {
        return self::$maps['purpose'] ?? [];
    }

    public static function purposeLabel($key): string
    {
        return self::$maps['purpose'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
    }
}
