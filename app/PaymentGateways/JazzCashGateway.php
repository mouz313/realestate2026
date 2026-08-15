<?php

namespace App\PaymentGateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class JazzCashGateway implements GatewayInterface
{
    public function name(): string
    {
        return 'JazzCash';
    }

    public function configured(): bool
    {
        $settings = company_settings();

        return ! empty($settings['jazzcash_merchant_id'])
            && ! empty($settings['jazzcash_password'])
            && ! empty($settings['jazzcash_secret_salt']);
    }

    public function createOrder(float $amount, string $reference, ?string $description = null): array
    {
        $settings = company_settings();
        $sandbox = (bool) ($settings['jazzcash_sandbox'] ?? true);
        $environment = $sandbox ? 'sandbox' : 'production';

        $merchantId = $settings['jazzcash_merchand_id'] ?? $settings['jazzcash_merchant_id'];
        $password = $settings['jazzcash_password'];
        $salt = $settings['jazzcash_secret_salt'];

        $txnRefId = 'AGENCY'.now()->timestamp.Str::random(6);
        $returnUrl = route('gateway.return');
        $callbackUrl = route('gateway.callback');

        $payload = [
            'pp_Version' => '4',
            'pp_TxnType' => 'MWAL',
            'pp_TxnRefID' => $txnRefId,
            'pp_Amount' => (int) round($amount * 100), // in paisas
            'pp_Currency' => $settings['currency'] ?? 'PKR',
            'pp_BillDescription' => $description ?? 'Agency payment',
            'pp_InvoiceNumber' => $reference,
            'pp_ReturnURL' => $returnUrl,
            'pp_ReturnMethod' => 'GET',
            'pp_CallbackURL' => $callbackUrl,
            'pp_Secure_Encryption' => 'true',
            'pp_Merchant' => $merchantId,
            'pp_Password' => $password,
            'pp_Salt' => $salt,
        ];

        $payload['pp_Signature'] = $this->generateSignature($payload);

        $endpoint = $sandbox
            ? 'https://sandbox.jazzcash.com.pk/gateway/api/webPayment'
            : 'https://pay.jazzcash.com.pk/gateway/api/webPayment';

        $payload['_environment'] = $environment;

        // Order is recorded by the dispatcher; here we store gateway data and return the
        // hosted-redirect URL with encoded payload. The hosted page consumes these fields.
        return [
            'redirect_url' => $endpoint.'?'.http_build_query($payload),
            'order_id' => $txnRefId,
            'currency' => $settings['currency'] ?? 'PKR',
        ];
    }

    public function verify(string $orderId): bool
    {
        $settings = company_settings();
        $sandbox = (bool) ($settings['jazzcash_sandbox'] ?? true);
        $endpoint = $sandbox
            ? 'https://sandbox.jazzcash.com.pk/gateway/api/PaymentInquiry'
            : 'https://pay.jazzcash.com.pk/gateway/api/PaymentInquiry';

        $response = Http::post($endpoint, [
            'pp_TxnRefID' => $orderId,
            'pp_Merchant' => $settings['jazzcash_merchant_id'] ?? $settings['jazzcash_merchand_id'],
            'pp_Password' => $settings['jazzcash_password'],
            'pp_Salt' => $settings['jazzcash_secret_salt'],
            'pp_Signature' => $this->generateSignature(['pp_TxnRefID' => $orderId]),
            'pp_ResponseMode' => 'JSON',
        ]);

        if ($response->successful()) {
            $body = $response->json();

            return isset($body['pp_TxnRefID']) && ($body['pp_PaymentStatus'] ?? '') === 'SUCCESS';
        }

        return false;
    }

    protected function generateSignature(array $fields): string
    {
        $settings = company_settings();
        ksort($fields);
        $concatenated = implode('', array_values($fields));

        return hash_hmac('sha256', $concatenated, $settings['jazzcash_secret_salt'] ?? '');
    }

    public function nameSlug(): string
    {
        return 'jazzcash';
    }
}
