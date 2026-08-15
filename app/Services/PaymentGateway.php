<?php

namespace App\Services;

use App\PaymentGateways\EasyPaisaGateway;
use App\PaymentGateways\GatewayInterface;
use App\PaymentGateways\JazzCashGateway;
use App\PaymentGateways\RaastGateway;
use InvalidArgumentException;

class PaymentGateway
{
    public function for(string $gateway): GatewayInterface
    {
        return match ($gateway) {
            'jazzcash' => new JazzCashGateway(),
            'easypaisa' => new EasyPaisaGateway(),
            'raast' => new RaastGateway(),
            default => throw new InvalidArgumentException("Unsupported gateway [{$gateway}]."),
        };
    }

    public function supportsOnlineConfirmation(string $gateway): bool
    {
        return in_array($gateway, ['jazzcash', 'easypaisa'], true);
    }
}
