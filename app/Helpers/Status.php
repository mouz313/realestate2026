<?php

namespace App\Helpers;

class Status
{
    protected static array $maps = [
        'property' => [
            'available' => 'status-available',
            'sold' => 'status-sold',
            'rented' => 'status-rented',
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
            'phone_call' => 'Phone Call',
            'other' => 'Other',
        ],
        'category' => [
            'house' => 'House',
            'plot' => 'Plot',
            'farmhouse' => 'Farmhouse',
            'agricultural_land' => 'Agricultural Land',
            'flat' => 'Flat',
            'studio_apartment' => 'Studio Apartment',
            'office' => 'Office',
            'shop' => 'Shop',
        ],
        'transaction_type' => [
            'sale' => 'Sale',
            'buy' => 'Buy',
            'rent' => 'Rent',
            'installment' => 'Installment',
        ],
        'call_status' => [
            'new' => 'New',
            'contacted' => 'Contacted',
            'callback' => 'Callback',
            'matched' => 'Matched',
            'converted' => 'Converted',
            'lost' => 'Lost',
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
        'contact' => [
            'open' => 'status-active',
            'pending' => 'status-pending',
            'closed' => 'status-completed',
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

    public static function categoryLabel($key): string
    {
        return self::$maps['category'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
    }

    public static function transactionTypeLabel($key): string
    {
        return self::$maps['transaction_type'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
    }

    public static function callStatusLabel($key): string
    {
        return self::$maps['call_status'][$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
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
