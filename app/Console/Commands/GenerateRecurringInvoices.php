<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Console\Command;

class GenerateRecurringInvoices extends Command
{
    protected $signature = 'invoices:generate-recurring';

    protected $description = 'Generate recurring invoices that are due';

    public function handle(): void
    {
        $due = Invoice::where('is_recurring', true)
            ->where('recurring_next_date', '<=', now()->format('Y-m-d'))
            ->with('client', 'items')
            ->get();

        if ($due->isEmpty()) {
            $this->info('No recurring invoices due.');

            return;
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        $paymentTerms = (int) ($settings['payment_terms'] ?? 30);

        foreach ($due as $invoice) {
            $newInvoice = Invoice::create([
                'client_id' => $invoice->client_id,
                'invoice_number' => $this->nextNumber(),
                'status' => 'unpaid',
                'due_date' => now()->addDays($paymentTerms),
                'subtotal' => $invoice->subtotal,
                'discount_type' => $invoice->discount_type,
                'discount_value' => $invoice->discount_value,
                'discount_amount' => $invoice->discount_amount,
                'tax_rate' => $invoice->tax_rate,
                'tax_amount' => $invoice->tax_amount,
                'total' => $invoice->total,
                'paid_amount' => 0,
                'payment_status' => 'pending',
            ]);

            foreach ($invoice->items as $item) {
                $newInvoice->items()->create([
                    'item_name' => $item->item_name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->line_total,
                ]);
            }

            $nextDate = match ($invoice->recurring_frequency) {
                'monthly' => now()->addMonth()->format('Y-m-d'),
                'quarterly' => now()->addMonths(3)->format('Y-m-d'),
                'yearly' => now()->addYear()->format('Y-m-d'),
                default => now()->addMonth()->format('Y-m-d'),
            };

            $invoice->update(['recurring_next_date' => $nextDate]);

            $this->info('Generated invoice '.$newInvoice->invoice_number.' from '.$invoice->invoice_number);
        }
    }

    private function nextNumber(): string
    {
        $prefix = 'INV-';
        $last = Invoice::latest()->first();

        $number = $last ? intval(substr($last->invoice_number, 4)) + 1 : 1;

        return $prefix.str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
