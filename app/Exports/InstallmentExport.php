<?php

namespace App\Exports;

use App\Models\Installment;

class InstallmentExport
{
    public static function build(): array
    {
        $headers = ['ID', 'Plan', 'No', 'Due Date', 'Amount', 'Paid Amount', 'Status', 'Paid Date', 'Payment Method', 'Reference'];

        $rows = Installment::with('plan')->orderBy('due_date', 'desc')->get()->map(function ($i) {
            return [
                $i->id,
                $i->plan?->name ?? '-',
                $i->installment_no,
                $i->due_date?->format('Y-m-d'),
                $i->amount,
                $i->paid_amount,
                str_replace('_', ' ', $i->status),
                $i->paid_date?->format('Y-m-d'),
                ucfirst(str_replace('_', ' ', $i->payment_method ?? '-')),
                $i->reference_no ?? '',
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
