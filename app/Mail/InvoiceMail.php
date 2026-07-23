<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice '.$this->invoice->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $this->invoice->load('client', 'items'),
            'settings' => Setting::pluck('value', 'key')->toArray(),
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-'.$this->invoice->invoice_number.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
