<?php

namespace App\Exports;

use App\Models\Payment;

class PaymentExport
{
    public static function build(): array
    {
        $query = Payment::with(['invoice.client', 'rentAgreement.tenant']);

        $headers = ['ID', 'Type', 'Invoice / Agreement', 'Party', 'Amount', 'Method', 'Reference', 'Paid Date', 'Type (row)'];

        $rows = $query->latest()->get()->map(function ($p) {
            $type = $p->invoice_id ? 'Invoice' : 'Rent Agreement';
            $ref = $p->invoice_id
                ? ($p->invoice?->invoice_number ?? 'INV-'.$p->invoice_id)
                : 'AGREE-'.$p->rent_agreement_id;
            $party = $p->invoice?->client?->name ?? $p->rentAgreement?->tenant?->name ?? '-';

            return [
                $p->id,
                $type,
                $ref,
                $party,
                $p->amount,
                ucfirst(str_replace('_', ' ', $p->method ?? '-')),
                $p->reference,
                $p->paid_date?->format('Y-m-d'),
                ucfirst(str_replace('_', ' ', $p->payment_type ?? '-')),
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
