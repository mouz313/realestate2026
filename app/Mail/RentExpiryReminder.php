<?php

namespace App\Mail;

use App\Models\RentAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RentAgreement $agreement,
        public string $recipientType,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Agreement Expiring Soon — Property: '.($this->agreement->property?->title ?? 'N/A'),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function buildHtml(): string
    {
        $a = $this->agreement;
        $property = $a->property;
        $daysLeft = now()->diffInDays($a->end_date);

        if ($this->recipientType === 'tenant') {
            $recipientName = $a->tenant?->name ?? 'Tenant';
        } else {
            $recipientName = $a->owner?->name ?? 'Owner';
        }

        $propertyTitle = $property?->title ?? 'N/A';
        $endDate = $a->end_date->format('d M Y');
        $appName = config('app.name');

        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;'>
            <div style='background:#ffc107;color:#333;padding:20px;text-align:center;'>
                <h2 style='margin:0;'>Agreement Expiring Soon</h2>
            </div>
            <div style='padding:20px;border:1px solid #eee;'>
                <p>Dear {$recipientName},</p>
                <p>Your rent agreement is expiring in <strong>{$daysLeft} days</strong>. Please take necessary action.</p>
                <table style='width:100%;border-collapse:collapse;'>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Property</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$propertyTitle}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>End Date</strong></td><td style='padding:8px;border-bottom:1px solid #eee;color:#dc3545;'>{$endDate}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Monthly Rent</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>Rs. ".number_format($a->rent_amount, 2)."</td></tr>
                </table>
                <p style='margin-top:20px;color:#666;font-size:12px;'>This is an automated notification from {$appName}</p>
            </div>
        </div>";
    }
}
