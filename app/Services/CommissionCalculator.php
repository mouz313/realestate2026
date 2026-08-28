<?php

namespace App\Services;

use App\Models\Deal;

/**
 * Commission rules (locked from spec):
 *
 * The agency's fee = commission_rate% of the deal value (sale price, or
 * monthly rent for rentals), collected 50% from landlord + 50% from tenant.
 * The agency keeps 90% and pays the closing agent 10% of the total fee.
 *
 * `sourced_by_agent_id` is informational only and is NEVER used in the calc.
 */
class CommissionCalculator
{
    public const AGENT_SHARE = 0.10;

    public const AGENCY_SHARE = 0.90;

    /**
     * Split a known total fee into agency/agent portions (90/10).
     */
    public static function split(float $fee): array
    {
        $fee = round((float) $fee, 2);
        $agent = round($fee * self::AGENT_SHARE, 2);
        $agency = round($fee - $agent, 2);

        return [
            'commission_amount' => $fee,
            'agency_amount' => $agency,
            'agent_amount' => $agent,
        ];
    }

    /**
     * Compute the split for a deal, mirroring DealController::syncDealExtras.
     */
    public static function forDeal(Deal $deal): array
    {
        $rate = $deal->commission_percentage ?? optional($deal->property)->commission_rate;
        $base = $deal->sale_price ?: optional($deal->property)->price;

        $fee = $deal->commission_amount;
        if (is_null($fee) && $base && ! is_null($rate)) {
            $fee = $base * $rate / 100;
        }
        $fee = (float) ($fee ?? 0);

        return array_merge(
            ['rate' => $rate],
            self::split($fee)
        );
    }
}
