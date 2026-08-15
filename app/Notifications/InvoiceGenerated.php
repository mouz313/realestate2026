<?php

namespace App\Notifications;

use App\Models\Invoice;

class InvoiceGenerated extends BaseNotification
{
    public function __construct(public Invoice $invoice) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Invoice generated',
            'message' => "Invoice #{$this->invoice->invoice_number} of PKR ".number_format($this->invoice->total, 0).' created.',
            'url' => route('portal.invoices.show', $this->invoice),
        ];
    }

    protected function sms(): string
    {
        return "Invoice #{$this->invoice->invoice_number} of PKR ".number_format($this->invoice->total, 0).' created. Please check your portal.';
    }
}
