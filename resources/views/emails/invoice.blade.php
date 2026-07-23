<!DOCTYPE html>
<html>
<head><title>Invoice {{ $invoice->invoice_number }}</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>{{ $settings['business_name'] ?? config('app.name') }}</h2>
    <p>Dear {{ optional($invoice->client)->name ?? 'Valued Customer' }},</p>
    <p>Please find attached your invoice <strong>{{ $invoice->invoice_number }}</strong>.</p>
    <p>Total Amount: <strong>{{ number_format($invoice->total, 2) }}</strong></p>
    <p>Due Date: {{ $invoice->due_date?->format('d M Y') ?? 'N/A' }}</p>
    <p>You can view this invoice and make payments through your client portal.</p>
    <p>Thank you for your business.</p>
</body>
</html>
