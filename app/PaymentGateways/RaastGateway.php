<?php

namespace App\PaymentGateways;

use App\Helpers\RaastQR;
use Illuminate\Support\Str;

class RaastGateway implements GatewayInterface
{
    public function name(): string
    {
        return 'Raast';
    }

    public function configured(): bool
    {
        $settings = company_settings();

        return ! empty($settings['raast_iban']);
    }

    public function createOrder(float $amount, string $reference, ?string $description = null): array
    {
        $settings = company_settings();
        $iban = $settings['raast_iban'] ?? '';

        $link = RaastQR::generatePaymentLink(
            (string) $amount,
            $reference,
            $iban,
        );

        return [
            'redirect_url' => $link,
            'order_id' => 'RAAST'.$reference.Str::random(4),
            'currency' => $settings['currency'] ?? 'PKR',
            'is_manual' => true,
        ];
    }

    public function verify(string $orderId): bool
    {
        // Raast P2P transfers cannot be programmatically confirmed via a public
        // sandbox API; confirmation is performed manually by staff. We resolve
        // the payment here only when the staff marks it verified, so this method
        // never auto-confirms.
        return false;
    }
}
