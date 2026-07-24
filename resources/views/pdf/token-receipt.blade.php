<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Token Receipt {{ $deal->deal_number }}</title>
    <style>
        @page { margin: 15px 25px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #2d2d2d; line-height: 1.5; margin: 0; padding: 0; }

        .top-bar {
            background: #0f172a; color: #fff; padding: 18px 28px; border-radius: 6px 6px 0 0;
        }
        .top-bar .left { float: left; width: 55%; }
        .top-bar .right { float: right; width: 45%; text-align: right; padding-top: 4px; }
        .top-bar::after { content: ''; display: table; clear: both; }
        .logo-img { max-height: 38px; max-width: 160px; display: inline-block; vertical-align: middle; }
        .co-name { font-size: 16px; font-weight: 800; letter-spacing: .3px; display: inline-block; vertical-align: middle; margin-left: 8px; }
        .doc-label { font-size: 20px; font-weight: 900; letter-spacing: 1.5px; text-transform: uppercase; color: #f97316; }
        .doc-ref { font-size: 8px; color: rgba(255,255,255,.6); margin-top: 2px; letter-spacing: .3px; }

        .body-wrap { padding: 18px 28px 0; }

        .sec-title {
            font-size: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;
            color: #f97316; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1px solid #e2e8f0;
        }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.info td { padding: 3px 6px; font-size: 8.5px; }
        table.info td:first-child { width: 150px; color: #64748b; font-weight: 600; }

        .amount-words { font-style: italic; color: #64748b; font-size: 8px; }

        ol { padding-left: 18px; margin: 4px 0 0; }
        ol li { margin-bottom: 3px; font-size: 8.5px; }

        .signatures { margin-top: 15px; }
        .signatures table { width: 100%; border-collapse: collapse; }
        .signatures td { width: 33%; text-align: center; padding-top: 28px; font-size: 8.5px; color: #64748b; }

        .footer-note {
            text-align: center; font-size: 6.5px; color: #94a3b8; border-top: 1px solid #e2e8f0;
            padding: 8px 28px 0; margin: 0 -28px;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="left">
            @if(!empty($settings['brand_logo']))
            <img src="{{ storage_path('app/public/'.$settings['brand_logo']) }}" class="logo-img" alt="Logo">
            @endif
            <span class="co-name">{{ $settings['business_name'] ?? config('app.name') }}</span>
        </div>
        <div class="right">
            <div class="doc-label">Token Receipt</div>
            <div class="doc-ref">#{{ $deal->deal_number }}-TKN &nbsp;|&nbsp; {{ $token->received_date ? $token->received_date->format('d M Y') : now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="body-wrap">

        <div class="sec-title">Receipt</div>
        <table class="info">
            <tr><td>Received From</td><td>{{ $deal->buyer->name ?? 'N/A' }}</td></tr>
            <tr><td>Property</td><td>{{ $deal->property->title ?? 'N/A' }}, {{ $deal->property->location_address ?? '' }}</td></tr>
            <tr><td>Sale Price</td><td>Rs. {{ number_format($deal->sale_price, 2) }}</td></tr>
            <tr><td>Token Amount</td><td>Rs. {{ number_format($token->amount, 2) }}</td></tr>
            <tr><td>In Words</td><td class="amount-words">Rs. {{ number_format($token->amount, 2) }} only</td></tr>
            <tr><td>Payment Method</td><td>{{ $token->payment_method ?? 'N/A' }}</td></tr>
            <tr><td>Reference</td><td>{{ $token->reference_no ?? 'N/A' }}</td></tr>
        </table>

        <div class="sec-title">Terms</div>
        <ol>
            <li>This token is part of the total sale consideration.</li>
            <li>If Buyer backs out, this amount may be forfeited.</li>
            <li>If Seller backs out, double shall be refunded to Buyer.</li>
            <li>Balance payment as per agreement.</li>
        </ol>

        <div class="sec-title">Signatures</div>
        <div class="signatures">
            <table>
                <tr>
                    <td><strong>Receiver</strong><br>_________________________</td>
                    <td><strong>Payer</strong><br>_________________________</td>
                    <td><strong>Witness</strong><br>_________________________</td>
                </tr>
            </table>
        </div>

        <div class="footer-note">
            <strong>{{ $settings['business_name'] ?? config('app.name') }}</strong>
            &mdash; {{ $settings['business_address'] ?? '' }}
            &mdash; {{ $settings['business_phone'] ?? '' }}
            &mdash; {{ $settings['business_email'] ?? '' }}
        </div>

    </div>

</body>
</html>
