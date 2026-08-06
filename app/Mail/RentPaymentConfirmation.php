<?php

namespace App\Mail;

use App\Models\RentPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RentPayment $rentPayment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received — '.$this->rentPayment->month_name,
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
        $rp = $this->rentPayment;
        $agreement = $rp->rentAgreement;
        $property = $agreement?->property;
        $tenant = $agreement?->tenant;

        $tenantName = $tenant?->name ?? 'Tenant';
        $propertyTitle = $property?->title ?? 'N/A';
        $paymentMethod = ucfirst(str_replace('_', ' ', $rp->payment_method ?? '-'));
        $reference = $rp->reference_no ?? '-';
        $appName = config('app.name');

        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;'>
            <div style='background:#1a1a2e;color:#D4A24E;padding:20px;text-align:center;'>
                <h2 style='margin:0;'>Payment Received</h2>
            </div>
            <div style='padding:20px;border:1px solid #eee;'>
                <p>Dear {$tenantName},</p>
                <p>We confirm receipt of your rent payment:</p>
                <table style='width:100%;border-collapse:collapse;'>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Property</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$propertyTitle}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Month</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$rp->month_name}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Amount Paid</strong></td><td style='padding:8px;border-bottom:1px solid #eee;color:#28a745;font-weight:bold;'>Rs. ".number_format($rp->amount, 2)."</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Late Fee</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>Rs. ".number_format($rp->late_fee, 2)."</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Total</strong></td><td style='padding:8px;border-bottom:1px solid #eee;font-weight:bold;'>Rs. ".number_format($rp->total_due, 2)."</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Payment Method</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$paymentMethod}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Reference</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$reference}</td></tr>
                </table>
                <p style='margin-top:20px;color:#666;font-size:12px;'>This is an automated notification from {$appName}</p>
            </div>
        </div>";
    }
}
