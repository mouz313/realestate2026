<!DOCTYPE html>
<html>
<head><title>Quotation {{ $quotation->quote_number }}</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>{{ $settings['business_name'] ?? config('app.name') }}</h2>
    <p>Dear {{ optional($quotation->client)->name ?? 'Valued Customer' }},</p>
    <p>Please find attached your quotation <strong>{{ $quotation->quote_number }}</strong>.</p>
    <p>Total Amount: <strong>{{ number_format($quotation->total, 2) }}</strong></p>
    <p>You can view and respond to this quotation through your client portal.</p>
    <p>Thank you for your business.</p>
</body>
</html>
