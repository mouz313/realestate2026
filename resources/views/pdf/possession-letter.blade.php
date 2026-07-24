<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Possession Letter {{ $deal->deal_number }}</title>
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
        p { margin: 0 0 5px; font-size: 8.5px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.info td { padding: 3px 6px; font-size: 8.5px; }
        table.info td:first-child { width: 170px; color: #64748b; font-weight: 600; }

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
            <div class="doc-label">Possession Letter</div>
            <div class="doc-ref">{{ $deal->possession_date ? $deal->possession_date->format('d M Y') : now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="body-wrap">

        <div class="sec-title">Property</div>
        <table class="info">
            <tr><td>Title</td><td>{{ $deal->property->title ?? 'N/A' }}</td></tr>
            <tr><td>Location</td><td>{{ $deal->property->location_address ?? 'N/A' }}, {{ $deal->property->city ?? '' }}</td></tr>
        </table>

        <div class="sec-title">Certification</div>
        <p>This is to certify that possession of the above property has been handed over by:</p>
        <p><strong>SELLER:</strong> {{ $deal->seller->name ?? 'N/A' }}</p>
        <p><strong>TO BUYER:</strong> {{ $deal->buyer->name ?? 'N/A' }}</p>
        <p>All dues have been cleared and the property is in satisfactory condition.</p>

        <div class="sec-title">Handover</div>
        <table class="info">
            <tr><td>Keys Handed Over</td><td>________ sets</td></tr>
            <tr><td>Electric Meter</td><td>________</td></tr>
            <tr><td>Gas Meter</td><td>________</td></tr>
            <tr><td>Water Meter</td><td>________</td></tr>
        </table>

        <div class="sec-title">Signatures</div>
        <div class="signatures">
            <table>
                <tr>
                    <td><strong>Seller</strong><br>_________________________</td>
                    <td><strong>Buyer</strong><br>_________________________</td>
                    <td><strong>Agent</strong><br>_________________________</td>
                </tr>
                <tr>
                    <td><strong>Witness</strong><br>_________________________</td>
                    <td></td>
                    <td></td>
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
