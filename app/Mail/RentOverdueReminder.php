<?php

namespace App\Mail;

use App\Models\RentPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentOverdueReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RentPayment $rentPayment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Overdue Rent Payment — '.$this->rentPayment->month_name,
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
        $daysPast = now()->diffInDays($rp->due_date);

        $tenantName = $tenant?->name ?? 'Tenant';
        $propertyTitle = $property?->title ?? 'N/A';
        $dueDate = $rp->due_date->format('d M Y');
        $appName = config('app.name');

        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;'>
            <div style='background:#dc3545;color:#fff;padding:20px;text-align:center;'>
                <h2 style='margin:0;'>Overdue Rent Payment</h2>
            </div>
            <div style='padding:20px;border:1px solid #eee;'>
                <p>Dear {$tenantName},</p>
                <p>Your rent payment is <strong>{$daysPast} days overdue</strong>. Please arrange payment immediately to avoid additional late fees.</p>
                <table style='width:100%;border-collapse:collapse;'>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Property</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$propertyTitle}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Month</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>{$rp->month_name}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Due Date</strong></td><td style='padding:8px;border-bottom:1px solid #eee;color:#dc3545;'>{$dueDate}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Rent Amount</strong></td><td style='padding:8px;border-bottom:1px solid #eee;'>Rs. ".number_format($rp->amount, 2)."</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Late Fee</strong></td><td style='padding:8px;border-bottom:1px solid #eee;color:#dc3545;font-weight:bold;'>Rs. ".number_format($rp->late_fee, 2)."</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #eee;'><strong>Total Due</strong></td><td style='padding:8px;border-bottom:1px solid #eee;font-weight:bold;'>Rs. ".number_format($rp->total_due, 2)."</td></tr>
                </table>
                <p style='margin-top:20px;color:#666;font-size:12px;'>This is an automated notification from {$appName}</p>
            </div>
        </div>";
    }
}
