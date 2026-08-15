<?php

namespace App\Exports;

use App\Models\RentPayment;
use Illuminate\Support\Facades\Auth;

class RentPaymentExport
{
    public static function build(): array
    {
        $agentId = Auth::user()->isAgent() ? Auth::user()->agent_id : null;

        $query = RentPayment::with(['rentAgreement.property', 'rentAgreement.tenant', 'rentAgreement.owner'])
            ->when($agentId, fn ($q) => $q->whereHas('rentAgreement', fn ($rq) => $rq->where(function ($q2) use ($agentId) {
                $q2->where('agent_id', $agentId)
                    ->orWhere('assigned_agent_id', $agentId);
            })))
            ->latest();

        $headers = ['ID', 'Property', 'Tenant', 'Owner', 'Month', 'Year', 'Amount', 'Late Fee', 'Total Due', 'Due Date', 'Paid Date', 'Status', 'Payment Method'];

        $rows = $query->get()->map(function ($rp) {
            return [
                $rp->id,
                $rp->rentAgreement?->property?->title ?? '-',
                $rp->rentAgreement?->tenant?->name ?? '-',
                $rp->rentAgreement?->owner?->name ?? '-',
                $rp->month,
                $rp->year,
                $rp->amount,
                $rp->late_fee,
                $rp->total_due,
                $rp->due_date?->format('Y-m-d'),
                $rp->paid_date?->format('Y-m-d'),
                str_replace('_', ' ', $rp->status),
                ucfirst(str_replace('_', ' ', $rp->payment_method ?? '-')),
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
