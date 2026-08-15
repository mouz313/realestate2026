<?php

namespace App\Exports;

use App\Models\Deal;
use Illuminate\Support\Facades\Auth;

class DealExport
{
    public static function build(?array $filters = null): array
    {
        if ($filters === null) {
            $filters = request()->validate([
                'status' => 'nullable|string',
                'type' => 'nullable|string',
                'search' => 'nullable|string',
            ]);

            $filters = array_intersect_key($filters, array_flip(['status', 'type', 'search']));
        }

        $agentId = Auth::user()->isAgent() ? Auth::user()->agent_id : null;

        $query = Deal::with(['property', 'buyer', 'seller', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->when($filters['status'] ?? null, fn ($q) => $q->where('status', $filters['status']))
            ->when($filters['type'] ?? null, fn ($q) => $q->where('type', $filters['type']))
            ->when($filters['search'] ?? null, function ($q, $s) {
                $q->where(function ($q2) use ($s) {
                    $q2->where('deal_number', 'like', "%{$s}%")
                        ->orWhereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$s}%"))
                        ->orWhereHas('buyer', fn ($cq) => $cq->where('name', 'like', "%{$s}%"))
                        ->orWhereHas('seller', fn ($cq) => $cq->where('name', 'like', "%{$s}%"));
                });
            })
            ->latest();

        $headers = ['Deal #', 'Type', 'Status', 'Property', 'Buyer', 'Seller', 'Agent', 'Sale Price', 'Commission %', 'Commission Amount', 'Agreement Date', 'Possession Date'];

        $rows = $query->get()->map(function ($deal) {
            return [
                $deal->deal_number,
                $deal->type,
                str_replace('_', ' ', $deal->status),
                $deal->property?->title ?? '',
                $deal->buyer?->name ?? '',
                $deal->seller?->name ?? '',
                $deal->agent?->name ?? '',
                $deal->sale_price,
                $deal->commission_percentage ?? '',
                $deal->commission_amount ?? '',
                $deal->agreement_date?->format('Y-m-d') ?? '',
                $deal->possession_date?->format('Y-m-d') ?? '',
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
