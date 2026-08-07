@extends('layouts.admin')

@section('title', 'New Invoice <span class="urdu">(نیا انوائس)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}" class="text-decoration-none">Invoices <span class="urdu">(انوائسز)</span></a></li>
        <li class="breadcrumb-item active">New Invoice <span class="urdu">(نیا انوائس)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
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
                        <label class="form-label">Due Date <span class="urdu">(آخری تاریخ)</span></label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate (%) <span class="urdu">(ٹیکس کی شرح)</span></label>
                        <input type="number" name="tax_rate" id="taxRate" step="0.01" min="0" max="100" class="form-control" value="{{ old('tax_rate', $taxRate) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount <span class="urdu">(چھوٹ)</span></label>
                        <div class="row g-2">
                            <div class="col-5">
                                <select name="discount_type" id="discountType" class="form-select">
                                    <option value="">No Discount</option>
                                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                            </div>
                            <div class="col-7">
                                <input type="number" name="discount_value" id="discountValue" step="0.01" min="0" class="form-control" value="{{ old('discount_value', 0) }}" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_recurring" value="1" id="recurringCheck" {{ old('is_recurring') ? 'checked' : '' }}>
                            <label class="form-check-label" for="recurringCheck">Recurring Invoice <span class="urdu">(مکرر انوائس)</span></label>
                        </div>
                    </div>
                    <div class="mb-3" id="recurringOptions" style="display: {{ old('is_recurring') ? 'block' : 'none' }};">
                        <label class="form-label">Frequency <span class="urdu">(تکرار)</span></label>
                        <select name="recurring_frequency" class="form-select">
                            <option value="monthly" {{ old('recurring_frequency') === 'monthly' ? 'selected' : '' }}>Monthly <span class="urdu">(ماہانہ)</span></option>
                            <option value="quarterly" {{ old('recurring_frequency') === 'quarterly' ? 'selected' : '' }}>Quarterly <span class="urdu">(سہ ماہی)</span></option>
                            <option value="yearly" {{ old('recurring_frequency') === 'yearly' ? 'selected' : '' }}>Yearly <span class="urdu">(سالانہ)</span></option>
                        </select>
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
                    <h5><i class="ti ti-list me-1"></i> Items <span class="urdu">(آئٹمز)</span></h5>
                    <div class="d-flex gap-2">
                        @if(isset($templates) && $templates->count())
                        <select id="templateSelect" class="form-select form-select-sm" style="width:auto;">
                            <option value="">Insert template...</option>
                            @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}"
                                data-name="{{ $tpl->name }}"
                                data-price="{{ $tpl->default_price }}"
                                data-unit="{{ $tpl->unit }}"
                                data-desc="{{ $tpl->description }}">{{ $tpl->name }} ({{ number_format($tpl->default_price, 0) }})</option>
                            @endforeach
                        </select>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="addRow()">
                            <i class="ti ti-plus"></i> Add Item <span class="urdu">(آئٹم شامل)</span>
                        </button>
                    </div>
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
                                    <td colspan="6" class="text-center text-secondary py-4">Add line items to this invoice. <span class="urdu">(اس انوائس میں آئٹمز شامل کریں)</span></td>
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
                                <tr id="discountRow" style="display:none;">
                                    <td class="text-end fw-semibold text-danger">Discount: <span class="urdu">(چھوٹ)</span></td>
                                    <td class="text-end text-danger" id="displayDiscount">0.00</td>
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
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="submit" class="btn btn-dark btn-lg"><i class="ti ti-device-floppy"></i> Save Invoice <span class="urdu">(انوائس محفوظ)</span></button>
                <a href="{{ route('invoices.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const templates = @json($templates ?? []);
let rowIndex = 0;

document.getElementById('templateSelect')?.addEventListener('change', function() {
    const val = this.value;
    if (!val) return;
    const tpl = templates.find(x => x.id == val);
    if (!tpl) return;
    addRow({
        item_name: tpl.name,
        unit: tpl.unit || '',
        unit_price: Number(tpl.default_price),
        description: tpl.description || '',
    });
    this.value = '';
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

    const discountType = document.getElementById('discountType').value;
    const discountVal = parseFloat(document.getElementById('discountValue').value) || 0;
    let discountAmount = 0;
    if (discountType === 'percentage') {
        discountAmount = subtotal * (discountVal / 100);
    } else if (discountType === 'fixed') {
        discountAmount = discountVal;
    }
    discountAmount = Math.min(discountAmount, subtotal);

    const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
    const taxable = subtotal - discountAmount;
    const tax = taxable * (taxRate / 100);

    document.getElementById('displaySubtotal').textContent = subtotal.toFixed(2);
    const discountRow = document.getElementById('discountRow');
    if (discountAmount > 0) {
        discountRow.style.display = '';
        document.getElementById('displayDiscount').textContent = '-' + discountAmount.toFixed(2);
    } else {
        discountRow.style.display = 'none';
    }
    document.getElementById('displayTax').textContent = tax.toFixed(2);
    document.getElementById('displayTotal').textContent = (taxable + tax).toFixed(2);
}

function removeRow(i) {
    const row = document.getElementById(`row-${i}`);
    if (row) row.remove();
    calcTotal();
    if (document.querySelectorAll('#itemsBody tr').length === 0) {
        document.getElementById('itemsBody').innerHTML = '<tr id="noItemsRow"><td colspan="6" class="text-center text-secondary py-4">Add line items to this invoice.</td></tr>';
    }
}

document.getElementById('taxRate').addEventListener('input', calcTotal);
document.getElementById('discountType').addEventListener('change', calcTotal);
document.getElementById('discountValue').addEventListener('input', calcTotal);

document.getElementById('recurringCheck').addEventListener('change', function() {
    document.getElementById('recurringOptions').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
