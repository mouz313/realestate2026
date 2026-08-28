<?php

namespace App\Helpers;

class Phone
{
    /**
     * Strip to digits only.
     */
    public static function digits(?string $phone): string
    {
        return preg_replace('/[^0-9]/', '', (string) $phone);
    }

    /**
     * Normalize a Pakistani phone number to a 92-prefixed digit string.
     * e.g. 0300... -> 92300..., 300... -> 92300..., 92300... -> 92300...
     */
    public static function normalize(?string $phone): string
    {
        $phone = self::digits($phone);

        if ($phone === '') {
            return '';
        }

        if (str_starts_with($phone, '0')) {
            return '92'.substr($phone, 1);
        }

        if (! str_starts_with($phone, '92')) {
            return '92'.$phone;
        }

        return $phone;
    }
}
