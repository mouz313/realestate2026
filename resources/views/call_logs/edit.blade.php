@extends('layouts.admin')

@section('title', 'Edit Call Log <span class="urdu">(کال لاگ ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('call-logs.index') }}" class="text-decoration-none">Call Logs <span class="urdu">(کال لاگ)</span></a></li>
        <li class="breadcrumb-item active">{{ $callLog->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-phone-edit me-1"></i> Edit Call Log <span class="urdu">(کال لاگ ترمیم)</span></h4>
    </div>
    <form action="{{ route('call-logs.update', $callLog) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Caller is <span class="urdu">(کال کرنے والا)</span></label>
                        <select name="caller_role" class="form-select">
                            <option value="">— Unknown —</option>
                            <option value="seller" @selected(old('caller_role', $callLog->caller_role) == 'seller')>Seller <span class="urdu">(بیچنے والا)</span></option>
                            <option value="buyer" @selected(old('caller_role', $callLog->caller_role) == 'buyer')>Buyer <span class="urdu">(خریدنے والا)</span></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to Existing Client <span class="urdu">(موجودہ گاہک سے جوڑیں)</span></label>
                        <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                            <option value="">— No client / link manually later —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id', $callLog->client_id) == $client->id)>{{ $client->name }} ({{ $client->phone ?? '' }})</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Clients are created only when a deal (sell/buy/rent) happens.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $callLog->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="urdu">(فون)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $callLog->phone) }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alternate Phone <span class="urdu">(متبادل فون)</span></label>
                        <input type="text" class="form-control" name="alternate_phone" value="{{ old('alternate_phone', $callLog->alternate_phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lead Source <span class="urdu">(ذریعہ)</span></label>
                        <select name="lead_source" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($leadSources as $key => $label)
                                <option value="{{ $key }}" @selected(old('lead_source', $callLog->lead_source) == $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="urdu">(زمرہ)</span></label>
                        <select name="category" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($categories as $c)
                                <option value="{{ $c }}" @selected(old('category', $callLog->category) == $c)>{{ ucfirst(str_replace('_', ' ', $c)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction Type <span class="urdu">(قسم)</span></label>
                        <select name="transaction_type" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($transactionTypes as $t)
                                <option value="{{ $t }}" @selected(old('transaction_type', $callLog->transaction_type) == $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City <span class="urdu">(شہر)</span></label>
                        <select name="city_id" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" @selected(old('city_id', $callLog->city_id) == $city->id)>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location <span class="urdu">(مقام)</span></label>
                        <input type="text" class="form-control" name="location" value="{{ old('location', $callLog->location) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bedrooms <span class="urdu">(بیڈروم)</span></label>
                        <input type="number" min="0" class="form-control" name="bedrooms" value="{{ old('bedrooms', $callLog->bedrooms) }}">
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Budget Min <span class="urdu">(کم از کم بجٹ)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" name="budget_min" value="{{ old('budget_min', $callLog->budget_min) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Budget Max <span class="urdu">(زیادہ سے زیادہ بجٹ)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" name="budget_max" value="{{ old('budget_max', $callLog->budget_max) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Assigned Agent <span class="urdu">(ایجنٹ)</span></label>
                        <select name="assigned_agent_id" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" @selected(old('assigned_agent_id', $callLog->assigned_agent_id) == $agent->id)>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Call Datetime <span class="urdu">(کال کا وقت)</span></label>
                        <input type="datetime-local" class="form-control" name="call_datetime" value="{{ old('call_datetime', $callLog->call_datetime ? $callLog->call_datetime->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Follow-up Date <span class="urdu">(فالو اپ کی تاریخ)</span></label>
                        <input type="date" class="form-control" name="follow_up_date" value="{{ old('follow_up_date', $callLog->follow_up_date ? $callLog->follow_up_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" @selected(old('status', $callLog->status) == $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes / Requirement <span class="urdu">(نوٹس / ضرورت)</span></label>
                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $callLog->notes) }}</textarea>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Call <span class="urdu">(اپ ڈیٹ)</span></button>
            <a href="{{ route('call-logs.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
