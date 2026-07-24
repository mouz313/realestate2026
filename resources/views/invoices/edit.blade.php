@extends('layouts.admin')

@section('title', 'Edit Invoice <span class="urdu">(انوائس میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}" class="text-decoration-none">Invoices <span class="urdu">(انوائسز)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none">{{ $invoice->invoice_number }}</a></li>
        <li class="breadcrumb-item active">Edit <span class="urdu">(ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="ti ti-info-circle me-1"></i> Details <span class="urdu">(معلومات)</span></h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Client <span class="urdu">(گاہک)</span> <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Select client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date <span class="urdu">(آخری تاریخ)</span></label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate (%) <span class="urdu">(ٹیکس کی شرح)</span></label>
                        <input type="number" name="tax_rate" id="taxRate" step="0.01" min="0" max="100" class="form-control" value="{{ old('tax_rate', $invoice->tax_rate) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5><i class="ti ti-list me-1"></i> Line Items <span class="urdu">(لائن آئٹمز)</span></h5>
                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="addRow()">
                        <i class="ti ti-plus"></i> Add Item <span class="urdu">(آئٹم شامل)</span>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Item <span class="urdu">(آئٹم)</span></th>
                                    <th style="width: 8%;">Qty <span class="urdu">(مقدار)</span></th>
                                    <th style="width: 10%;">Unit <span class="urdu">(یونٹ)</span></th>
                                    <th style="width: 15%;">Price <span class="urdu">(قیمت)</span></th>
                                    <th style="width: 15%;">Total <span class="urdu">(کل)</span></th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-end fw-semibold">Subtotal: <span class="urdu">(ذیلی کل)</span></td>
                                    <td style="width: 120px;" class="text-end" id="displaySubtotal">{{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-semibold">Tax <span class="urdu">(ٹیکس)</span> (<span id="displayTaxLabel">{{ $taxLabel }}</span>):</td>
                                    <td class="text-end" id="displayTax">{{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="text-end">Total: <span class="urdu">(کل)</span></td>
                                    <td class="text-end fs-5" id="displayTotal">{{ number_format($invoice->total, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-dark btn-lg"><i class="ti ti-device-floppy"></i> Update Invoice <span class="urdu">(انوائس اپ ڈیٹ)</span></button>
                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let rowIndex = 0;

function addRow(data = null) {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    const i = rowIndex++;
    tr.id = `row-${i}`;

    tr.innerHTML = `
        <td>
            <input type="text" name="items[${i}][item_name]" class="form-control form-control-sm item-name" value="${data?.item_name || ''}" placeholder="Item name" required>
            <input type="hidden" name="items[${i}][description]" class="item-desc" value="${data?.description || ''}">
        </td>
        <td><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm qty" value="${data?.quantity || 1}" min="1" oninput="calcRow(${i})"></td>
        <td><input type="text" name="items[${i}][unit]" class="form-control form-control-sm unit" value="${data?.unit || ''}" placeholder="unit"></td>
        <td><input type="number" name="items[${i}][unit_price]" class="form-control form-control-sm price" step="any" min="0" value="${data?.unit_price || 0}" oninput="calcRow(${i})"></td>
        <td class="text-end line-total pt-3" id="lineTotal-${i}">${((data?.quantity || 0) * (data?.unit_price || 0)).toFixed(2)}</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(${i})"><i class="ti ti-x"></i></button></td>
    `;

    tbody.appendChild(tr);
    calcTotal();
}

function calcRow(i) {
    const row = document.getElementById(`row-${i}`);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    document.getElementById(`lineTotal-${i}`).textContent = (qty * price).toFixed(2);
    calcTotal();
}

function calcTotal() {
    let subtotal = 0;
    document.querySelectorAll('.line-total').forEach(el => {
        subtotal += parseFloat(el.textContent) || 0;
    });
    const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
    const tax = subtotal * (taxRate / 100);
    document.getElementById('displaySubtotal').textContent = subtotal.toFixed(2);
    document.getElementById('displayTax').textContent = tax.toFixed(2);
    document.getElementById('displayTotal').textContent = (subtotal + tax).toFixed(2);
}

function removeRow(i) {
    const row = document.getElementById(`row-${i}`);
    if (row) row.remove();
    calcTotal();
}

@foreach($invoice->items as $item)
addRow({
    item_name: '{{ $item->item_name }}',
    quantity: {{ $item->quantity }},
    unit: '{{ $item->unit }}',
    unit_price: {{ $item->unit_price }},
});
@endforeach
</script>
@endpush
