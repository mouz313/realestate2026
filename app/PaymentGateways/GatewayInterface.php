<?php

namespace App\PaymentGateways;

interface GatewayInterface
{
    /**
     * Human-readable name used in settings UI.
     */
    public function name(): string;

    /**
     * Whether this gateway is configured (has merchant credentials).
     */
    public function configured(): bool;

    /**
     * Create an order on the gateway.
     *
     * @return array{redirect_url: string|null, order_id: string|null, currency: string}
     */
    public function createOrder(float $amount, string $reference, ?string $description = null): array;

    /**
     * Verify/confirm an order status from the gateway.
     */
    public function verify(string $orderId): bool;
}
