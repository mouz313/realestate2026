<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333333;
            line-height: 1.5; margin: 0; padding: 0; background: #FAFAFA;
        }
        .page { width: 100%; }

        {{-- HEADER --}}
        .header {
            background: #fff; padding: 24px 36px 20px;
            border-bottom: 3px solid #E8524B;
        }
        .header::after { content: ''; display: table; clear: both; }
        .h-left { float: left; }
        .logo-box {
            display: inline-block; width: 42px; height: 42px;
            background: #E8524B; color: #fff; font-size: 22px; font-weight: 900;
            text-align: center; line-height: 42px; border-radius: 4px;
            vertical-align: middle; margin-right: 10px;
        }
        .logo-box img { max-width: 42px; max-height: 42px; border-radius: 4px; display: block; }
        .h-left .brand {
            display: inline-block; vertical-align: middle;
        }
        .h-left .brand .co-name {
            font-size: 17px; font-weight: 800; text-transform: uppercase; color: #2B2B2B; letter-spacing: 0.5px;
        }
        .h-left .brand .co-tag {
            font-size: 8px; color: #8A8A8A; margin-top: 1px;
        }
        .h-right { float: right; text-align: right; }
        .h-right .doc-type {
            font-size: 30px; font-weight: 900; text-transform: uppercase;
            color: #2B2B2B; letter-spacing: 2px; line-height: 1.1;
        }
        .h-right .doc-num {
            font-size: 10px; color: #8A8A8A; margin-top: 2px;
        }

        {{-- BODY --}}
        .body { padding: 28px 36px 0; }

        {{-- BILL TO / INFO ROW --}}
        .info-row { margin-bottom: 24px; }
        .info-row::after { content: ''; display: table; clear: both; }
        .ir-left { float: left; width: 55%; }
        .ir-right { float: right; width: 40%; }

        .bill-label {
            font-size: 9px; text-transform: uppercase; font-weight: 700;
            letter-spacing: 1.5px; color: #E8524B; margin-bottom: 4px;
        }
        .bill-name { font-size: 13px; font-weight: 700; color: #2B2B2B; margin-bottom: 2px; }
        .bill-detail { font-size: 8.5px; color: #8A8A8A; line-height: 1.6; }

        .info-box {
            background: #fff; border: 1px solid #e5e5e5; border-radius: 5px;
            border-left: 4px solid #E8524B; padding: 10px 14px;
        }
        .info-box::after { content: ''; display: table; clear: both; }
        .ib-row { display: block; margin-bottom: 6px; }
        .ib-row:last-child { margin-bottom: 0; }
        .ib-label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.8px; color: #8A8A8A; }
        .ib-value { font-size: 10px; font-weight: 700; color: #2B2B2B; margin-top: 1px; white-space: nowrap; }

        {{-- ITEMS TABLE --}}
        .items-wrap { margin-bottom: 20px; }
        table.items {
            width: 100%; border-collapse: collapse;
        }
        table.items thead th {
            background: #E8524B; color: #fff;
            padding: 9px 12px; text-align: left;
            font-size: 9px; text-transform: uppercase; letter-spacing: 0.7px; font-weight: 700;
        }
        table.items thead th:first-child { border-radius: 5px 0 0 0; }
        table.items thead th:last-child { border-radius: 0 5px 0 0; }
        table.items tbody td {
            padding: 8px 12px; font-size: 9.5px; border-bottom: 1px solid #eee;
        }
        table.items tbody tr:nth-child(even) td { background: #F2F2F2; }
        table.items tbody tr:last-child td { border-bottom: 2px solid #E8524B; }
        table.items .item-name { font-weight: 700; font-size: 10px; }
        table.items .item-desc { font-size: 8px; color: #8A8A8A; margin-top: 1px; }
        .ta-c { text-align: center; }
        .ta-r { text-align: right; }
        .col-no { width: 36px; }

        {{-- BOTTOM --}}
        .bottom { width: 100%; margin-bottom: 20px; }
        .bottom::after { content: ''; display: table; clear: both; }
        .b-left { float: left; width: 52%; }
        .b-right { float: right; width: 44%; }

        .info-heading {
            font-size: 8px; text-transform: uppercase; letter-spacing: 1px;
            font-weight: 700; color: #E8524B; margin-bottom: 4px;
        }
        .info-text { font-size: 8.5px; color: #666; line-height: 1.6; margin-bottom: 12px; }

        {{-- TOTALS --}}
        .totals-box { }
        .totals-box table { width: 100%; border-collapse: collapse; }
        .totals-box td { padding: 3px 0; font-size: 10px; border: none; }
        .totals-box .lbl { color: #666; text-align: left; padding-right: 14px; }
        .totals-box .val { text-align: right; font-weight: 600; min-width: 100px; }
        .totals-box .sep td { padding: 0; }
        .totals-box .sep div { border-top: 1px solid #ddd; }
        .totals-box .grand td {
            font-weight: 800; font-size: 14px; color: #fff; padding: 0;
        }
        .totals-box .grand .val, .totals-box .grand .lbl {
            background: #E8524B; padding: 8px 12px; border-radius: 0 0 5px 5px;
        }
        .totals-box .grand .lbl { border-radius: 5px 0 0 5px; }
        .totals-box .grand .val { border-radius: 0 5px 5px 0; }

        {{-- STATUS BADGE --}}
        .badge {
            display: inline-block; padding: 2px 10px; border-radius: 8px;
            font-size: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-sent, .badge-pending { background: #dbeafe; color: #1e40af; }
        .badge-approved, .badge-paid { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-invoiced { background: #f3e8ff; color: #6b21a8; }
        .badge-partial { background: #fef3c7; color: #92400e; }
        .badge-overdue { background: #dc2626; color: #fff; }

        {{-- QR --}}
        .qr-wrap { text-align: center; margin-bottom: 10px; }
        .qr-wrap img { width: 60px; height: 60px; display: inline-block; }
        .qr-label { font-size: 5.5px; color: #bbb; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 1px; }

        {{-- SIGN & STAMP --}}
        .ss-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .ss-table td { padding: 0; vertical-align: top; }
        .ss-box-div {
            border: 1px solid #ddd; border-radius: 4px;
            padding: 18px 10px 8px; text-align: center;
        }
        .ss-label { font-size: 8.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #8A8A8A; }
        .ss-line { border-top: 1px solid #2B2B2B; width: 80%; margin: 14px auto 2px; }

        {{-- PROPERTY CARD --}}
        .prop-card {
            margin-bottom: 16px; border: 1px solid #e5e5e5; border-radius: 5px;
        }
        .prop-card-header {
            background: #f8f8f8; padding: 6px 12px; border-bottom: 1px solid #e5e5e5;
            font-size: 8px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #E8524B;
        }
        .prop-card-body { padding: 6px 8px; }
        .prop-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .prop-table td { padding: 4px 6px; vertical-align: top; }
        .prop-table .f-label { font-size: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #8A8A8A; margin-bottom: 2px; }
        .prop-table .f-value { font-size: 9px; font-weight: 600; color: #2B2B2B; line-height: 1.3; }

        {{-- SECTION TITLE --}}
        .sec-title {
            font-size: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;
            color: #E8524B; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1px solid #e5e5e5;
        }
        .sec-title.alt { color: #2B2B2B; border-bottom-color: #E8524B; }

        {{-- INFO TABLE --}}
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.info td { padding: 3px 6px; font-size: 8.5px; }
        table.info td:first-child { width: 150px; color: #8A8A8A; font-weight: 600; }

        p { margin: 0 0 5px; font-size: 8.5px; }
        .amount-words { font-style: italic; color: #8A8A8A; font-size: 8px; }
        ol { padding-left: 18px; margin: 4px 0 0; }
        ol li { margin-bottom: 3px; font-size: 8.5px; }

        {{-- AMOUNT BOX --}}
        .amount-box {
            background: #f8f8f8; border-radius: 6px; padding: 14px 18px; text-align: center; margin: 6px 0 16px;
            border: 1px solid #e5e5e5;
        }
        .amount-box .lbl { font-size: 8px; color: #8A8A8A; text-transform: uppercase; letter-spacing: .8px; }
        .amount-box .val { font-size: 22px; font-weight: 900; color: #2B2B2B; margin-top: 3px; }
        .amount-box .sub { font-size: 8px; color: #94a3b8; margin-top: 3px; }

        {{-- SIGNATURES --}}
        .signatures { margin-top: 16px; }
        .signatures table { width: 100%; border-collapse: collapse; }
        .signatures td { width: 33%; text-align: center; padding-top: 28px; font-size: 8.5px; color: #8A8A8A; }
        .signature-line { margin-top: 4px; font-size: 8px; color: #2B2B2B; font-weight: 600; }

        {{-- FOOTER --}}
        .footer {
            padding: 14px 36px 10px; margin: 0 -36px;
            border-top: 2px solid #E8524B; text-align: center;
        }
        .f-thanks { font-size: 12px; font-weight: 700; color: #E8524B; margin-bottom: 4px; }
        .f-co-name { font-size: 10px; font-weight: 700; color: #2B2B2B; }
        .f-co-detail { font-size: 7.5px; color: #8A8A8A; line-height: 1.5; margin-top: 2px; }

        {{-- NOTES --}}
        .notes-wrap { margin-bottom: 14px; }
        .notes-label {
            font-size: 8px; text-transform: uppercase; letter-spacing: 1px;
            font-weight: 700; color: #E8524B; margin-bottom: 2px;
        }
        .notes-text { font-size: 8.5px; color: #666; line-height: 1.55; }

        .clr { clear: both; }
    </style>
</head>
<body>
    <div class="page">

        {{-- HEADER --}}
        <div class="header">
            <div class="h-left">
                <div class="logo-box">
                    @if(!empty($settings['brand_logo']))
                        <img src="{{ storage_path('app/public/'.$settings['brand_logo']) }}" alt="">
                    @elseif(!empty($settings['business_name']))
                        {{ strtoupper(substr($settings['business_name'], 0, 1)) }}
                    @else
                        A
                    @endif
                </div>
                <div class="brand">
                    <div class="co-name">{{ $settings['business_name'] ?? config('app.name') }}</div>
                    <div class="co-tag">{{ $settings['business_address'] ?? '' }}</div>
                </div>
            </div>
            <div class="h-right">
                @hasSection('doc-type')
                    <div class="doc-type">@yield('doc-type')</div>
                @endif
                @hasSection('doc-number')
                    <div class="doc-num">@yield('doc-number')</div>
                @endif
            </div>
        </div>

        <div class="body">
            @yield('content')
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <div class="f-thanks">Thank you for your business!</div>
            <div class="f-co-name">{{ $settings['business_name'] ?? config('app.name') }}</div>
            <div class="f-co-detail">
                {{ $settings['business_address'] ?? '' }}<br>
                {{ $settings['business_phone'] ?? '' }} &nbsp;|&nbsp; {{ $settings['business_email'] ?? '' }}
            </div>
        </div>

    </div>
</body>
</html>
