<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Quotation $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation ' . $this->quotation->quote_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation',
        );
    }

    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotations.pdf', [
            'quotation' => $this->quotation->load('client', 'items'),
            'settings' => \App\Models\Setting::pluck('value', 'key')->toArray(),
        ]);
        
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'quotation-' . $this->quotation->quote_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
