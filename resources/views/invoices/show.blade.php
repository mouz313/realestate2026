@extends('layouts.admin')

@section('title', 'Invoice <span class="urdu">(انوائس)</span> ' . $invoice->invoice_number)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}" class="text-decoration-none">Invoices <span class="urdu">(انوائسز)</span></a></li>
        <li class="breadcrumb-item active">{{ $invoice->invoice_number }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>{{ $invoice->invoice_number }}</h3>
    <div class="page-header-sub">
        <span class="badge status-{{ $invoice->payment_status ?? 'pending' }}">{{ ucfirst($invoice->payment_status ?? 'pending') }}</span>
    </div>
    <div class="action-btns">
        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-dark"><i class="ti ti-file-download"></i> PDF <span class="urdu">(پی ڈی ایف)</span></a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-6">
                        <h5 class="mb-1">{{ $settings['business_name'] ?? config('app.name') }}</h5>
                        <p class="text-secondary mb-0 small">{{ $settings['business_address'] ?? '' }}</p>
                        <p class="text-secondary mb-0 small">{{ $settings['business_email'] ?? '' }}</p>
                        <p class="text-secondary mb-0 small">{{ $settings['business_phone'] ?? '' }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h5 class="mb-1">INVOICE <span class="urdu">(انوائس)</span></h5>
                        <p class="mb-0 small"># {{ $invoice->invoice_number }}</p>
                        <p class="mb-0 small">Date: <span class="urdu">(تاریخ)</span> {{ $invoice->created_at->format('d M Y') }}</p>
                        <p class="mb-0 small">Due: <span class="urdu">(آخری تاریخ)</span> {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</p>
                        <p class="mb-0 small">
                            <span class="badge status-{{ $invoice->payment_status ?? 'pending' }}">{{ ucfirst($invoice->payment_status ?? 'pending') }}</span>
                        </p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <h6 class="text-secondary mb-1">Bill To: <span class="urdu">(بل وصول کنندہ)</span></h6>
                        <p class="mb-0 fw-semibold">{{ $invoice->client->name ?? '-' }}</p>
                        <p class="mb-0 small text-secondary">{{ $invoice->client->company ?? '' }}</p>
                        <p class="mb-0 small text-secondary">{{ $invoice->client->address ?? '' }}</p>
                        <p class="mb-0 small text-secondary">{{ $invoice->client->email ?? '' }}</p>
                        <p class="mb-0 small text-secondary">{{ $invoice->client->phone ?? '' }}</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Item <span class="urdu">(آئٹم)</span></th>
                                <th class="text-center">Qty <span class="urdu">(مقدار)</span></th>
                                <th class="text-center d-none d-sm-table-cell">Unit <span class="urdu">(یونٹ)</span></th>
                                <th class="text-end">Price <span class="urdu">(قیمت)</span></th>
                                <th class="text-end">Total <span class="urdu">(کل)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td><span class="fw-semibold">{{ $item->item_name }}</span></td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center d-none d-sm-table-cell">{{ $item->unit ?? '-' }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-end fw-semibold">Subtotal: <span class="urdu">(ذیلی کل)</span></td>
                                <td class="text-end" style="width:120px">{{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-semibold">{{ $settings['tax_label'] ?? 'GST' }} ({{ $invoice->tax_rate }}%):</td>
                                <td class="text-end">{{ number_format($invoice->tax_amount, 2) }}</td>
                            </tr>
                            <tr class="fw-bold fs-5">
                                <td class="text-end">Total: <span class="urdu">(کل)</span></td>
                                <td class="text-end">{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-semibold text-success">Paid: <span class="urdu">(ادا شدہ)</span></td>
                                <td class="text-end text-success">{{ number_format($invoice->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-semibold text-danger">Balance: <span class="urdu">(بقیہ)</span></td>
                                <td class="text-end text-danger">{{ number_format($invoice->total - $invoice->paid_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($invoice->notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-secondary mb-1">Notes: <span class="urdu">(نوٹس)</span></h6>
                        <p class="mb-0">{{ $invoice->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="ti ti-currency-dollar me-1"></i> Payments <span class="urdu">(ادائیگیاں)</span></h5>
            </div>
            <div class="card-body">
                @if($invoice->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date <span class="urdu">(تاریخ)</span></th>
                                <th class="d-none d-sm-table-cell">Method <span class="urdu">(ذریعہ)</span></th>
                                <th class="text-end">Amount <span class="urdu">(رقم)</span></th>
                                <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $p)
                            <tr>
                                <td>{{ $p->paid_date->format('d M Y') }}</td>
                                <td class="d-none d-sm-table-cell">{{ $p->method ?? '-' }}</td>
                                <td class="text-end text-success">{{ number_format($p->amount, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('payments.edit', $p) }}" class="text-secondary text-decoration-none me-1"><i class="ti ti-edit"></i></a>
                                    <form action="{{ route('payments.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this payment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="ti ti-currency-dollar"></i>
                    <span>No payments recorded yet. <span class="urdu">(کوئی ادائیگی ریکارڈ نہیں)</span></span>
                </div>
                @endif

                @if(($invoice->total - $invoice->paid_amount) > 0)
                <hr>
                <h6 class="mb-2">Record Payment <span class="urdu">(ادائیگی ریکارڈ)</span></h6>
                <form action="{{ route('invoices.payments.store', $invoice) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Amount <span class="urdu">(رقم)</span></label>
                        <input type="number" name="amount" step="0.01" min="0.01" max="{{ $invoice->total - $invoice->paid_amount }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Method <span class="urdu">(ذریعہ)</span></label>
                        <select name="method" class="form-select">
                            <option value="">Select</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="jazzcash">JazzCash</option>
                            <option value="easypaisa">EasyPaisa</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Reference <span class="urdu">(حوالہ)</span></label>
                        <input type="text" name="reference" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Date <span class="urdu">(تاریخ)</span></label>
                        <input type="date" name="paid_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Notes <span class="urdu">(نوٹس)</span></label>
                        <input type="text" name="notes" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="ti ti-plus"></i> Add Payment <span class="urdu">(ادائیگی شامل)</span></button>
                </form>

                @if(($invoice->total - $invoice->paid_amount) > 0 && !empty($settings['bank_iban']))
                <hr>
                <h6 class="mb-2">Pay via Raast / IBAN <span class="urdu">(راست/آئی بین)</span></h6>
                <div class="text-center mb-2">
                    <?php
                        $upiString = $settings['bank_iban'] . '@raast';
                        $qrData = "upi://pay?pa={$upiString}&am=" . number_format($invoice->total - $invoice->paid_amount, 2, '.', '') . "&tn=" . urlencode($invoice->invoice_number) . "&cu=PKR";
                    ?>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrData) }}" alt="Raast QR" class="img-fluid border rounded" style="max-width:150px;">
                    <div class="mt-1 small">
                        <strong>IBAN:</strong> {{ $settings['bank_iban'] }}<br>
                        <strong>Amount:</strong> {{ number_format($invoice->total - $invoice->paid_amount, 2) }}<br>
                        <strong>Reference:</strong> {{ $invoice->invoice_number }}
                    </div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['business_phone'] ?? '') }}?text={{ urlencode('I have made a payment of PKR ' . number_format($invoice->total - $invoice->paid_amount, 2) . ' for invoice ' . $invoice->invoice_number . '. Please confirm.') }}" target="_blank" class="btn btn-success btn-sm mt-2 w-100">
                        <i class="ti ti-brand-whatsapp"></i> Confirm Payment via WhatsApp <span class="urdu">(واٹس ایپ سے تصدیق)</span>
                    </a>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
