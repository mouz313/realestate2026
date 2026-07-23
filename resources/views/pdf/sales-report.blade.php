<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .header p { color: #666; font-size: 11px; margin: 2px 0; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px 12px; border: 1px solid #ddd; text-align: center; }
        .summary .label { font-size: 10px; color: #666; }
        .summary .value { font-size: 14px; font-weight: bold; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.details th { background: #333; color: #fff; padding: 8px; text-align: left; font-size: 10px; }
        table.details td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        table.details tr:nth-child(even) { background: #f9f9f9; }
        .text-end { text-align: right; }
        .footer { position: fixed; bottom: 10px; text-align: center; font-size: 9px; color: #999; width: 100%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings['business_name'] ?? config('app.name') }}</h1>
        <p>{{ $settings['business_address'] ?? '' }}</p>
        <p>{{ $settings['business_phone'] ?? '' }} | {{ $settings['business_email'] ?? '' }}</p>
        <hr>
        <h2>Sales Report</h2>
        <p>{{ $start }} to {{ $end }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td>
                    <div class="label">Deals Closed</div>
                    <div class="value">{{ $deals->count() }}</div>
                </td>
                <td>
                    <div class="label">Total Volume</div>
                    <div class="value">{{ number_format($totalVolume, 0) }}</div>
                </td>
                <td>
                    <div class="label">Total Commission</div>
                    <div class="value">{{ number_format($totalCommission, 0) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th>Deal #</th>
                <th>Property</th>
                <th>Buyer</th>
                <th>Agent</th>
                <th class="text-end">Price</th>
                <th class="text-end">Commission</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deals as $d)
            <tr>
                <td>{{ $d->deal_number }}</td>
                <td>{{ $d->property?->title ?? '-' }}</td>
                <td>{{ $d->buyer?->name ?? '-' }}</td>
                <td>{{ $d->agent?->name ?? '-' }}</td>
                <td class="text-end">{{ number_format($d->sale_price ?? 0, 0) }}</td>
                <td class="text-end">{{ number_format($d->commission_amount ?? 0, 0) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:20px;color:#999;">No completed deals in this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} &middot; {{ config('app.name') }}
    </div>
</body>
</html>
