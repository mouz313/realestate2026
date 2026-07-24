<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Commission #{{ $commission->id }}</title>
    <style>
        @page { margin: 15px 25px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #2d2d2d; line-height: 1.45; margin: 0; padding: 0; }

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

        .amount-box {
            background: #f8fafc; border-radius: 6px; padding: 14px 18px; text-align: center; margin: 6px 0 16px;
            border: 1px solid #e2e8f0;
        }
        .amount-box .lbl { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: .8px; }
        .amount-box .val { font-size: 22px; font-weight: 900; color: #0f172a; margin-top: 3px; }
        .amount-box .sub { font-size: 8px; color: #94a3b8; margin-top: 3px; }

        .signatures { margin-top: 18px; }
        .signatures table { width: 100%; border-collapse: collapse; }
        .signatures td { width: 33%; text-align: center; padding-top: 30px; font-size: 8.5px; color: #64748b; }

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
            <div class="doc-label">Commission Invoice</div>
            <div class="doc-ref">#COM-{{ str_pad($commission->id, 4, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp; {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="body-wrap">

        <div class="sec-title">Agent Details</div>
        <table class="info">
            <tr><td>Name</td><td>{{ $commission->agent->name ?? 'N/A' }}</td></tr>
            <tr><td>CNIC</td><td>{{ $commission->agent->cnic ?? 'N/A' }}</td></tr>
            @if($commission->agent->phone)<tr><td>Phone</td><td>{{ $commission->agent->phone }}</td></tr>@endif
            @if($commission->agent->email)<tr><td>Email</td><td>{{ $commission->agent->email }}</td></tr>@endif
        </table>

        <div class="sec-title">Deal Details</div>
        <table class="info">
            <tr><td>Deal No</td><td>{{ $commission->deal->deal_number ?? 'N/A' }}</td></tr>
            <tr><td>Property</td><td>{{ $commission->deal->property->title ?? 'N/A' }}</td></tr>
            <tr><td>Sale Price</td><td>Rs. {{ number_format($commission->deal->sale_price ?? 0, 2) }}</td></tr>
        </table>

        <div class="amount-box">
            <div class="lbl">Commission Amount</div>
            <div class="val">Rs. {{ number_format($commission->amount, 2) }}</div>
            <div class="sub">Rate: {{ $commission->percentage ?? 0 }}% &bull; Status: {{ ucfirst($commission->status ?? 'pending') }}</div>
        </div>

        <div class="sec-title">Signatures</div>
        <div class="signatures">
            <table>
                <tr>
                    <td><strong>Agent</strong><br>_________________________</td>
                    <td><strong>Agency Rep</strong><br>_________________________</td>
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
