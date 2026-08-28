<?php

namespace App\Services;

class PricingService
{
    /**
     * Compute invoice/quotation totals from a subtotal.
     *
     * Mirrors the original per-controller math exactly:
     *  - percentage discount is applied against the subtotal, fixed discount
     *    is taken at face value, and the discount can never exceed subtotal;
     *  - tax is charged on the discounted (taxable) amount.
     *
     * @return array{subtotal:float,discount_type:?string,discount_value:float,discount_amount:float,tax_rate:float,tax_amount:float,total:float}
     */
    public static function compute(float $subtotal, $discountType, $discountValue, $taxRate): array
    {
        $discountType = $discountType ?: null;
        $discountValue = (float) ($discountValue ?? 0);
        $discountAmount = $discountType === 'percentage'
            ? $subtotal * ($discountValue / 100)
            : $discountValue;
        $discountAmount = min($discountAmount, $subtotal);

        $taxRate = (float) ($taxRate ?? 0);
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($taxRate / 100);
        $total = $taxableAmount + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'discountType' => $discountType,
            'discountValue' => $discountValue,
            'discountAmount' => $discountAmount,
            'taxRate' => $taxRate,
            'taxAmount' => $taxAmount,
            'total' => $total,
        ];
    }
}
