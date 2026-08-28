<?php

namespace App\Helpers;

class Money
{
    /**
     * Format a numeric amount with thousands separators and a currency prefix.
     * e.g. Money::format(5000000, 'PKR') => "PKR 5,000,000"
     */
    public static function format($amount, string $currency = 'PKR', int $decimals = 0): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        return $currency.' '.number_format((float) $amount, $decimals);
    }
}
