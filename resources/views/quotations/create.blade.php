@extends('layouts.admin')

@section('title', 'New Quotation <span class="urdu">(نیا کوٹیشن)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}" class="text-decoration-none">Quotations <span class="urdu">(کوٹیشنز)</span></a></li>
        <li class="breadcrumb-item active">New Quotation <span class="urdu">(نیا کوٹیشن)</span></li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.property-card {
    background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px 16px;
    display: none; margin-bottom: 16px;
}
.property-card.active { display: block; }
.property-card .pc-title { font-weight: 700; font-size: .95rem; }
.property-card .pc-detail { font-size: .78rem; color: #64748b; }
.property-card .pc-price { font-weight: 800; color: #0f172a; font-size: 1.1rem; }
</style>
@endpush

@section('content')
<form action="{{ route('quotations.store') }}" method="POST" id="quotationForm">
    @csrf
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
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} {{ $client->company ? '- ' . $client->company : '' }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property <span class="urdu">(پراپرٹی)</span></label>
                        <select name="property_id" id="propertySelect" class="form-select @error('property_id') is-invalid @enderror">
                            <option value="">-- None (manual items) --</option>
                            @foreach($properties as $p)
                                <option value="{{ $p->id }}"
                                    data-title="{{ $p->title }}"
                                    data-price="{{ $p->price }}"
                                    data-location="{{ $p->location_address ?? '' }}, {{ $p->city ?? '' }}"
                                    data-type="{{ ucfirst($p->type) }}"
                                    data-size="{{ $p->plot_size ? $p->plot_size . ' ' . ($p->plot_size_unit ?? '') : '' }}"
                                    {{ old('property_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->title }} — {{ $p->city ?? '' }} (Rs. {{ number_format($p->price, 0) }})
                                </option>
                            @endforeach
                        </select>
                        @error('property_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="property-card" id="propertyCard">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="pc-title" id="pcTitle"></div>
                                <div class="pc-detail" id="pcDetail"></div>
                                <div class="pc-detail" id="pcType"></div>
                            </div>
                            <div class="pc-price" id="pcPrice"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date <span class="urdu">(میعاد ختم)</span></label>
                        <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', now()->addDays(15)->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate (%) <span class="urdu">(ٹیکس کی شرح)</span></label>
                        <input type="number" name="tax_rate" id="taxRate" step="0.01" min="0" max="100" class="form-control" value="{{ old('tax_rate', $taxRate) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
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
                                <tr id="noItemsRow">
                                    <td colspan="6" class="text-center text-secondary py-4">Add line items to this quotation. <span class="urdu">(اس کوٹیشن میں آئٹمز شامل کریں)</span></td>
                                </tr>
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
                                    <td style="width: 120px;" class="text-end" id="displaySubtotal">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-semibold">Tax <span class="urdu">(ٹیکس)</span> (<span id="displayTaxLabel">{{ $taxLabel }}</span>):</td>
                                    <td class="text-end" id="displayTax">0.00</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="text-end">Total: <span class="urdu">(کل)</span></td>
                                    <td class="text-end fs-5" id="displayTotal">0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-dark btn-lg"><i class="ti ti-device-floppy"></i> Save Quotation <span class="urdu">(کوٹیشن محفوظ)</span></button>
                <a href="{{ route('quotations.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const properties = @json($properties);
let rowIndex = 0;

{{-- Property selector --}}
const propSelect = document.getElementById('propertySelect');
const propCard = document.getElementById('propertyCard');

propSelect.addEventListener('change', function() {
    const val = this.value;
    if (!val) { propCard.classList.remove('active'); return; }
    const p = properties.find(x => x.id == val);
    if (!p) return;
    document.getElementById('pcTitle').textContent = p.title;
    document.getElementById('pcDetail').textContent = (p.location_address || '') + (p.city ? ', ' + p.city : '');
    document.getElementById('pcType').textContent = (p.type ? p.type.charAt(0).toUpperCase() + p.type.slice(1) : '') + (p.plot_size ? ' — ' + p.plot_size + ' ' + (p.plot_size_unit || '') : '');
    document.getElementById('pcPrice').textContent = 'Rs. ' + Number(p.price).toLocaleString(undefined, {minimumFractionDigits: 0});
    propCard.classList.add('active');

    {{-- Auto-add property price row if not already added --}}
    const existing = document.querySelectorAll('.item-name');
    let found = false;
    existing.forEach(el => { if (el.value === p.title) found = true; });
    if (!found) {
        addRow({
            item_name: p.title,
            quantity: 1,
            unit: 'lot',
            unit_price: Number(p.price),
        });
    }
});

function addRow(data = null) {
    document.getElementById('noItemsRow')?.remove();
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
    if (document.querySelectorAll('#itemsBody tr').length === 0) {
        document.getElementById('itemsBody').innerHTML = '<tr id="noItemsRow"><td colspan="6" class="text-center text-secondary py-4">Add line items to this quotation.</td></tr>';
    }
}

document.getElementById('taxRate').addEventListener('input', calcTotal);
</script>
@endpush
