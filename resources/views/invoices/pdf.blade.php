<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1e293b;
            line-height: 1.5; margin: 0; padding: 0;
        }

        .page { padding: 0; }

        .header {
            background: #0f172a; color: #fff; padding: 20px 32px; position: relative;
        }
        .header::after {
            content: ''; position: absolute; bottom: 0; left: 32px; right: 32px;
            height: 3px; background: #f97316;
        }
        .header-inner { width: 100%; }
        .header-inner::after { content: ''; display: table; clear: both; }
        .h-left { float: left; width: 55%; }
        .h-right { float: right; width: 45%; text-align: right; }
        .logo-wrap { display: inline-block; vertical-align: middle; }
        .logo-img { max-height: 40px; max-width: 160px; display: block; }
        .co-name {
            display: inline-block; vertical-align: middle; margin-left: 10px;
            font-size: 17px; font-weight: 800; letter-spacing: 0.3px;
        }
        .doc-type {
            font-size: 20px; font-weight: 900; letter-spacing: 1.8px;
            text-transform: uppercase; color: #f97316;
        }
        .doc-meta {
            font-size: 7.5px; color: rgba(255,255,255,0.5); margin-top: 2px;
        }
        .doc-meta .d { display: inline-block; }

        .body { padding: 20px 32px 0; }

        .parties { width: 100%; margin-bottom: 14px; }
        .parties::after { content: ''; display: table; clear: both; }
        .party-box {
            width: 48%; padding: 10px 12px; border: 1px solid #e2e8f0;
            border-radius: 4px; background: #fafafa;
        }
        .party-box-left { float: left; }
        .party-box-right { float: right; }
        .p-label {
            font-size: 6.5px; text-transform: uppercase; letter-spacing: 1.2px;
            font-weight: 700; color: #f97316; margin-bottom: 3px;
        }
        .p-name { font-size: 10.5px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .p-detail { font-size: 7.5px; color: #64748b; line-height: 1.55; }

        .info-row { margin-bottom: 14px; }
        .info-row::after { content: ''; display: table; clear: both; }
        .info-row .ir-left { float: left; }
        .info-row .ir-right { float: right; }
        .badge {
            display: inline-block; padding: 3px 12px; border-radius: 10px;
            font-size: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
        }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-partial { background: #fef3c7; color: #92400e; }
        .badge-pending, .badge-unpaid { background: #fee2e2; color: #991b1b; }
        .badge-overdue {
            background: #dc2626; color: #fff;
        }
        .due-info {
            font-size: 7.5px; color: #64748b; padding: 3px 0;
        }
        .due-info strong { color: #0f172a; }

        .items-wrap { margin-bottom: 14px; }
        table.items {
            width: 100%; border-collapse: collapse;
        }
        table.items thead th {
            background: #0f172a; color: #fff;
            padding: 7px 10px; text-align: left;
            font-size: 7px; text-transform: uppercase; letter-spacing: 0.7px; font-weight: 700;
        }
        table.items thead th:first-child { border-radius: 4px 0 0 0; }
        table.items thead th:last-child { border-radius: 0 4px 0 0; }
        table.items tbody td {
            padding: 6px 10px; font-size: 8.5px; border-bottom: 1px solid #e2e8f0;
        }
        table.items tbody tr:nth-child(even) td { background: #fafafa; }
        table.items tbody tr:last-child td { border-bottom: 2px solid #f97316; }
        .ta-c { text-align: center; }
        .ta-r { text-align: right; }

        .bottom { width: 100%; margin-bottom: 14px; }
        .bottom::after { content: ''; display: table; clear: both; }
        .qr-col { float: left; width: 100px; }
        .qr-box { display: inline-block; text-align: center; }
        .qr-box img { display: block; }
        .qr-label {
            font-size: 5.5px; color: #cbd5e1; text-transform: uppercase;
            letter-spacing: 0.8px; margin-top: 1px;
        }
        .totals { float: right; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td {
            padding: 2.5px 0; font-size: 9px; border: none;
        }
        .totals .lbl { color: #64748b; text-align: left; padding-right: 20px; }
        .totals .val { text-align: right; font-weight: 600; min-width: 100px; }
        .totals .sep td { padding: 0; }
        .totals .sep div { border-top: 1px solid #e2e8f0; }
        .totals .grand td {
            font-weight: 800; font-size: 12px; color: #0f172a; padding-top: 4px;
        }
        .totals .grand .val { border-top: 2px solid #0f172a; padding-top: 4px; }

        .meta-wrap {
            border-top: 1px solid #e2e8f0; padding-top: 10px; margin-bottom: 10px;
        }
        .meta-wrap::after { content: ''; display: table; clear: both; }
        .meta-col { float: left; width: 33%; }
        .meta-label {
            font-size: 6.5px; text-transform: uppercase; letter-spacing: 0.8px;
            font-weight: 700; color: #f97316; margin-bottom: 2px;
        }
        .meta-text { font-size: 7.5px; color: #64748b; line-height: 1.5; }

        .footer {
            text-align: center; font-size: 6px; color: #94a3b8; line-height: 1.6;
            border-top: 1px solid #e2e8f0; padding: 8px 32px 0; margin: 0 -32px;
        }
        .footer .f1 { font-size: 6.5px; color: #64748b; }
        .footer .sep { color: #cbd5e1; margin: 0 5px; }
    </style>
</head>
<body>
    <div class="page">

        <div class="header">
            <div class="header-inner">
                <div class="h-left">
                    @if(!empty($settings['brand_logo']))
                    <div class="logo-wrap">
                        <img src="{{ storage_path('app/public/'.$settings['brand_logo']) }}" class="logo-img" alt="Logo">
                    </div>
                    @endif
                    <span class="co-name">{{ $settings['business_name'] ?? config('app.name') }}</span>
                </div>
                <div class="h-right">
                    <div class="doc-type">Invoice</div>
                    <div class="doc-meta">
                        <span class="d">Ref: #{{ $invoice->invoice_number }}</span>
                        <span class="d">&nbsp;&bull;&nbsp;</span>
                        <span class="d">{{ $invoice->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="body">

            <div class="parties">
                <div class="party-box party-box-left">
                    <div class="p-label">From</div>
                    <div class="p-name">{{ $settings['business_name'] ?? config('app.name') }}</div>
                    <div class="p-detail">
                        {{ $settings['business_address'] ?? '' }}<br>
                        {{ $settings['business_email'] ?? '' }} &nbsp;|&nbsp; {{ $settings['business_phone'] ?? '' }}
                    </div>
                </div>
                <div class="party-box party-box-right">
                    <div class="p-label">Bill To</div>
                    <div class="p-name">{{ $invoice->client->name }}</div>
                    <div class="p-detail">
                        @if($invoice->client->company){{ $invoice->client->company }}<br>@endif
                        @if($invoice->client->address){{ $invoice->client->address }}<br>@endif
                        {{ $invoice->client->email }} &nbsp;|&nbsp; {{ $invoice->client->phone }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="ir-left">
                    @php
                        $status = $invoice->payment_status === 'paid' ? 'paid' : ($invoice->payment_status === 'partial' ? 'partial' : 'pending');
                    @endphp
                    <span class="badge badge-{{ $status }}">{{ ucfirst($invoice->payment_status) }}</span>
                    @if($invoice->due_date && $invoice->due_date->isPast() && $invoice->payment_status !== 'paid')
                    <span class="badge badge-overdue" style="margin-left:4px;">Overdue</span>
                    @endif
                </div>
                <div class="ir-right">
                    <div class="due-info">
                        @if($invoice->due_date)
                        <strong>Due date:</strong> {{ $invoice->due_date->format('d M Y') }}
                        @else
                        <span style="color:#cbd5e1;">No due date</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="items-wrap">
                <table class="items">
                    <thead>
                        <tr>
                            <th style="width:44%;">Description</th>
                            <th style="width:9%;" class="ta-c">Qty</th>
                            <th style="width:10%;" class="ta-c">Unit</th>
                            <th style="width:16%;" class="ta-r">Unit Price</th>
                            <th style="width:16%;" class="ta-r">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $item)
                        <tr>
                            <td><strong>{{ $item->item_name }}</strong></td>
                            <td class="ta-c">{{ $item->quantity }}</td>
                            <td class="ta-c">{{ $item->unit ?? '-' }}</td>
                            <td class="ta-r">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="ta-r">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#94a3b8;padding:16px;">No items listed.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bottom">
                <div class="qr-col">
                    <div class="qr-box">
                        <img src="{{ $qrCode }}" width="70" height="70" alt="QR">
                        <div class="qr-label">Scan to verify</div>
                    </div>
                </div>
                <div class="totals">
                    <table>
                        <tr>
                            <td class="lbl">Subtotal</td>
                            <td class="val">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">{{ $settings['tax_label'] ?? 'GST' }} ({{ $invoice->tax_rate }}%)</td>
                            <td class="val">{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr class="sep"><td colspan="2"><div></div></td></tr>
                        <tr class="grand">
                            <td class="lbl">Total</td>
                            <td class="val">{{ number_format($invoice->total, 2) }}</td>
                        </tr>
                        @if($invoice->paid_amount > 0)
                        <tr>
                            <td class="lbl" style="color:#16a34a;">Paid</td>
                            <td class="val" style="color:#16a34a;">-{{ number_format($invoice->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="lbl" style="color:#dc2626;font-weight:700;">Balance Due</td>
                            <td class="val" style="color:#dc2626;font-weight:700;">{{ number_format($invoice->total - $invoice->paid_amount, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="meta-wrap">
                <div class="meta-col">
                    @if($invoice->notes)
                    <div class="meta-label">Notes</div>
                    <div class="meta-text">{{ $invoice->notes }}</div>
                    @endif
                </div>
                <div class="meta-col">
                    @if(!empty($settings['payment_terms']))
                    <div class="meta-label">Payment Terms</div>
                    <div class="meta-text">Net {{ $settings['payment_terms'] }} days</div>
                    @endif
                </div>
                <div class="meta-col">
                    @if(!empty($settings['payment_methods']))
                    <div class="meta-label">Accepted Payments</div>
                    <div class="meta-text">{{ $settings['payment_methods'] }}</div>
                    @endif
                </div>
            </div>

            <div class="footer">
                <div class="f1">{{ $settings['business_name'] ?? config('app.name') }}</div>
                <div>
                    {{ $settings['business_address'] ?? '' }}
                    <span class="sep">&mdash;</span>
                    {{ $settings['business_phone'] ?? '' }}
                    <span class="sep">&mdash;</span>
                    {{ $settings['business_email'] ?? '' }}
                </div>
                @if(!empty($settings['bank_name']) || !empty($settings['bank_account']))
                <div>
                    @if(!empty($settings['bank_name']))Bank: {{ $settings['bank_name'] }}@endif
                    @if(!empty($settings['bank_account']))<span class="sep">&bull;</span>A/C: {{ $settings['bank_account'] }}@endif
                </div>
                @endif
            </div>

        </div>

    </div>
</body>
</html>