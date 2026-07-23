<?php

namespace App\Helpers;

class RaastQR
{
    public static function generatePaymentLink(string $amount, string $reference, string $iban = ''): string
    {
        $params = http_build_query([
            'amount' => $amount,
            'reference' => $reference,
            'iban' => $iban,
        ]);
        return route('payments.raast.redirect') . '?' . $params;
    }

    public static function qrCodeData(string $iban, string $amount, string $reference): array
    {
        return [
            'iban' => $iban,
            'amount' => $amount,
            'reference' => $reference,
            'upi' => $iban . '@raast',
        ];
    }
}
