@extends('layouts.admin')

@section('title', 'Edit Client <span class="urdu">(گاہک کی ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}" class="text-decoration-none">Clients <span class="urdu">(گاہک)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.show', $client) }}" class="text-decoration-none">{{ $client->name }}</a></li>
        <li class="breadcrumb-item active">Edit <span class="urdu">(ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-user me-1"></i> Edit Client <span class="urdu">(گاہک کی ترمیم)</span></h4>
    </div>
    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $client->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company <span class="urdu">(کمپنی)</span></label>
                        <input type="text" class="form-control" name="company" value="{{ old('company', $client->company) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $client->email) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="urdu">(فون)</span></label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $client->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address <span class="urdu">(پتہ)</span></label>
                        <textarea class="form-control" name="address" rows="3">{{ old('address', $client->address) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes', $client->notes) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Portal Password <span class="urdu">(پورٹل پاس ورڈ)</span> <span class="text-secondary small"><span class="urdu">(موجودہ رکھنے کے لیے خالی چھوڑیں)</span></span></label>
                        <input type="password" class="form-control" name="password" placeholder="Enter new password to change <span class="urdu">(نیا پاس ورڈ درج کریں)</span>">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Client <span class="urdu">(گاہک اپ ڈیٹ)</span></button>
            <a href="{{ route('clients.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
