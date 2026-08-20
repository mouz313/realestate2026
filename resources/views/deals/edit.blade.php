@extends('layouts.admin')

@section('title', 'Edit Deal <span class="urdu">(ڈیل میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('deals.index') }}" class="text-decoration-none">Deals <span class="urdu">(ڈیلز)</span></a></li>
        <li class="breadcrumb-item active">Edit Deal <span class="urdu">(ڈیل میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-building-store"></i> <span class="urdu">(ڈیل میں ترمیم کریں)</span></h4>
    </div>
    <form action="{{ route('deals.update', $deal) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Type <span class="urdu">(قسم)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                            <option value=""><span class="urdu">(قسم منتخب کریں)</span></option>
                            <option value="sale" {{ old('type', $deal->type) == 'sale' ? 'selected' : '' }}>Sale</option>
                            <option value="rent" {{ old('type', $deal->type) == 'rent' ? 'selected' : '' }}>Rent</option>
                            <option value="lease" {{ old('type', $deal->type) == 'lease' ? 'selected' : '' }}>Lease</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property <span class="urdu">(جائیداد)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('property_id') is-invalid @enderror" name="property_id" required>
                            <option value=""><span class="urdu">(جائیداد منتخب کریں)</span></option>
                            @foreach($properties ?? [] as $property)
                                <option value="{{ $property->id }}" {{ old('property_id', $deal->property_id) == $property->id ? 'selected' : '' }}>{{ $property->title }} ({{ $property->property_code ?? $property->id }})</option>
                            @endforeach
                        </select>
                        @error('property_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buyer <span class="urdu">(خریدار)</span></label>
                        <select class="form-select" name="buyer_select" id="buyer_select">
                            <option value="">— Select existing —</option>
                            <option value="__new__" @selected(old('buyer_name'))>➕ Add new buyer <span class="urdu">(نیا خریدار)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" @selected(old('buyer_id', $deal->buyer_id) == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="buyer_id" id="buyer_id" value="{{ old('buyer_id', $deal->buyer_id) }}">
                        @error('buyer_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="row g-2 mt-2" id="buyer_new" style="display:@if(old('buyer_name')) block @else none @endif;">
                            <div class="col">
                                <input type="text" class="form-control @error('buyer_name') is-invalid @enderror" name="buyer_name" placeholder="Buyer name" value="{{ old('buyer_name') }}">
                                @error('buyer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col">
                                <input type="text" class="form-control @error('buyer_phone') is-invalid @enderror" name="buyer_phone" placeholder="Buyer phone" value="{{ old('buyer_phone') }}">
                                @error('buyer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-text">Client is created only when this deal is saved.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seller <span class="urdu">(فروخت کنندہ)</span></label>
                        <select class="form-select" name="seller_select" id="seller_select">
                            <option value="">— Select existing —</option>
                            <option value="__new__" @selected(old('seller_name'))>➕ Add new seller <span class="urdu">(نیا فروخت کنندہ)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" @selected(old('seller_id', $deal->seller_id) == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="seller_id" id="seller_id" value="{{ old('seller_id', $deal->seller_id) }}">
                        @error('seller_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="row g-2 mt-2" id="seller_new" style="display:@if(old('seller_name')) block @else none @endif;">
                            <div class="col">
                                <input type="text" class="form-control @error('seller_name') is-invalid @enderror" name="seller_name" placeholder="Seller name" value="{{ old('seller_name') }}">
                                @error('seller_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col">
                                <input type="text" class="form-control @error('seller_phone') is-invalid @enderror" name="seller_phone" placeholder="Seller phone" value="{{ old('seller_phone') }}">
                                @error('seller_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-text">Client is created only when this deal is saved.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agent <span class="urdu">(ایجنٹ)</span></label>
                        <select class="form-select @error('agent_id') is-invalid @enderror" name="agent_id">
                            <option value=""><span class="urdu">(ایجنٹ منتخب کریں)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $deal->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Co-Agent <span class="urdu">(کو-ایجنٹ)</span> <span class="text-secondary small">(optional)</span></label>
                        <select class="form-select @error('co_agent_id') is-invalid @enderror" name="co_agent_id">
                            <option value=""><span class="urdu">(کو-ایجنٹ منتخب کریں)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('co_agent_id', $deal->co_agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('co_agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(حالت)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value=""><span class="urdu">(حالت منتخب کریں)</span></option>
                            @foreach(['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $deal->status ?? '') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lead Source <span class="urdu">(لیڈ کا ذریعہ)</span></label>
                        <select class="form-select @error('lead_source') is-invalid @enderror" name="lead_source">
                            <option value=""><span class="urdu">(ذریعہ منتخب کریں)</span></option>
                            @foreach($leadSources ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ old('lead_source', $deal->lead_source) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('lead_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sale Price <span class="urdu">(فروخت قیمت)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" name="sale_price" value="{{ old('sale_price', $deal->sale_price) }}" required>
                        @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commission Percentage <span class="urdu">(کمیشن فیصد)</span> (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('commission_percentage') is-invalid @enderror" name="commission_percentage" value="{{ old('commission_percentage', $deal->commission_percentage) }}">
                        @error('commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commission Amount <span class="urdu">(کمیشن رقم)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('commission_amount') is-invalid @enderror" name="commission_amount" value="{{ old('commission_amount', $deal->commission_amount) }}" placeholder="Auto-calculated if percentage is set">
                        @error('commission_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agent Commission <span class="urdu">(ایجنٹ کمیشن)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('agent_commission') is-invalid @enderror" name="agent_commission" value="{{ old('agent_commission', $deal->agent_commission) }}">
                        @error('agent_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agency Share <span class="urdu">(ایجنسی کا حصہ)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('agency_share') is-invalid @enderror" name="agency_share" value="{{ old('agency_share', $deal->agency_share) }}">
                        @error('agency_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Token Amount <span class="urdu">(ٹوکن رقم)</span></label>
                        <input type="number" step="0.01" class="form-control @error('token_amount') is-invalid @enderror" name="token_amount" value="{{ old('token_amount', $deal->token_amount) }}">
                        @error('token_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Token Date <span class="urdu">(ٹوکن تاریخ)</span></label>
                        <input type="date" class="form-control @error('token_date') is-invalid @enderror" name="token_date" value="{{ old('token_date', $deal->token_date ? $deal->token_date->format('Y-m-d') : '') }}">
                        @error('token_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agreement Date <span class="urdu">(معاہدے کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('agreement_date') is-invalid @enderror" name="agreement_date" value="{{ old('agreement_date', $deal->agreement_date ? $deal->agreement_date->format('Y-m-d') : '') }}">
                        @error('agreement_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Possession Date <span class="urdu">(قبضے کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('possession_date') is-invalid @enderror" name="possession_date" value="{{ old('possession_date', $deal->possession_date ? $deal->possession_date->format('Y-m-d') : '') }}">
                        @error('possession_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Plan <span class="urdu">(ادائیگی کا منصوبہ)</span> <span class="text-secondary small">(JSON format)</span></label>
                        <textarea class="form-control @error('payment_plan') is-invalid @enderror" name="payment_plan" rows="3">{{ old('payment_plan', $deal->payment_plan) }}</textarea>
                        @error('payment_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $deal->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> <span class="urdu">(ڈیل اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('deals.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function bindClientToggle(selectId, idField, newBox) {
        const select = document.getElementById(selectId);
        const hidden = document.getElementById(idField);
        const box = document.getElementById(newBox);
        function apply() {
            if (select.value === '__new__') {
                hidden.value = '';
                box.style.display = 'flex';
            } else {
                hidden.value = select.value;
                box.style.display = 'none';
            }
        }
        select.addEventListener('change', apply);
        apply();
    }
    bindClientToggle('buyer_select', 'buyer_id', 'buyer_new');
    bindClientToggle('seller_select', 'seller_id', 'seller_new');
</script>
@endpush
@endsection
