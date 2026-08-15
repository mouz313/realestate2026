<?php

namespace App\PaymentGateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EasyPaisaGateway implements GatewayInterface
{
    public function name(): string
    {
        return 'EasyPaisa';
    }

    public function configured(): bool
    {
        $settings = company_settings();

        return ! empty($settings['easypaisa_merchant_id'])
            && ! empty($settings['easypaisa_secret_key']);
    }

    public function createOrder(float $amount, string $reference, ?string $description = null): array
    {
        $settings = company_settings();
        $sandbox = (bool) ($settings['easypaisa_sandbox'] ?? true);

        $endpoint = $sandbox
            ? 'https://apg-test.easypaisa.com.pk/ep-service/api/AGSBankOrder'
            : 'https://apg.easypaisa.com.pk/ep-service/api/AGSBankOrder';

        $orderId = 'EP'.now()->timestamp.Str::random(6);

        $payload = [
            'merchantId' => $settings['easypaisa_merchant_id'],
            'orderReference' => $reference,
            'amount' => (int) round($amount * 100),
            'currencyCode' => 'PKR',
            'description' => $description ?? 'Agency payment',
            'callbackUrl' => route('gateway.callback'),
            'returnUrl' => route('gateway.return'),
            'expiryDateTime' => now()->addDay()->toIso8601String(),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$settings['easypaisa_secret_key'],
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        if ($response->successful()) {
            $body = $response->json();
            $checkoutUrl = $body['checkoutUrl'] ?? null;
            $gatewayOrderId = $body['orderId'] ?? $orderId;
        } else {
            $checkoutUrl = null;
            $gatewayOrderId = $orderId;
        }

        return [
            'redirect_url' => $checkoutUrl,
            'order_id' => $gatewayOrderId,
            'currency' => 'PKR',
        ];
    }

    public function verify(string $orderId): bool
    {
        $settings = company_settings();
        $sandbox = (bool) ($settings['easypaisa_sandbox'] ?? true);
        $endpoint = $sandbox
            ? 'https://apg-test.easypaisa.com.pk/ep-service/api/OrderStatus'
            : 'https://apg.easypaisa.com.pk/ep-service/api/OrderStatus';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$settings['easypaisa_secret_key'],
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'merchantId' => $settings['easypaisa_merchant_id'],
            'orderId' => $orderId,
        ]);

        if ($response->successful()) {
            $body = $response->json();

            return ($body['orderStatus'] ?? '') === 'SUCCESS' || ($body['status'] ?? '') === 'Success';
        }

        return false;
    }
}
