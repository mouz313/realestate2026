<?php

namespace App\Mail;

use App\Models\PortalAction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientActionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PortalAction $action,
        public string $adminEmail,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->adminEmail,
            subject: 'Client '.ucfirst($this->action->action).' — '.$this->action->quotation->quote_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.client-action',
            with: [
                'action' => $this->action,
                'quotation' => $this->action->quotation,
                'client' => $this->action->client,
            ],
        );
    }
}
