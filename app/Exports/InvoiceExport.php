<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceExport
{
    public static function build(): array
    {
        $agentId = Auth::user()->isAgent() ? Auth::user()->agent_id : null;

        $query = Invoice::with('client')
            ->when($agentId, fn ($q) => $q->whereHas('client', fn ($cq) => $cq->where('created_by', $agentId)))
            ->latest();

        $headers = ['Invoice #', 'Client', 'Type', 'Issue Date', 'Due Date', 'Subtotal', 'Tax', 'Discount', 'Total', 'Paid', 'Balance Due', 'Status', 'Payment Status'];

        $rows = $query->get()->map(function ($i) {
            return [
                $i->invoice_number,
                $i->client?->name ?? '-',
                str_replace('_', ' ', $i->invoice_type),
                $i->created_at?->format('Y-m-d'),
                $i->due_date?->format('Y-m-d'),
                $i->subtotal,
                $i->tax_amount,
                $i->discount_amount,
                $i->total,
                $i->paid_amount,
                $i->total - $i->paid_amount,
                str_replace('_', ' ', $i->status),
                str_replace('_', ' ', $i->payment_status),
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
