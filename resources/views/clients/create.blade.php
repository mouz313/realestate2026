@extends('layouts.admin')

@section('title', 'Add Client <span class="urdu">(نیا گاہک)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}" class="text-decoration-none">Clients <span class="urdu">(گاہک)</span></a></li>
        <li class="breadcrumb-item active">Add Client <span class="urdu">(نیا گاہک)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-user-plus me-1"></i> Add New Client <span class="urdu">(نیا گاہک)</span></h4>
    </div>
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company <span class="urdu">(کمپنی)</span></label>
                        <input type="text" class="form-control" name="company" value="{{ old('company') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="urdu">(فون)</span></label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address <span class="urdu">(پتہ)</span></label>
                        <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Portal Password <span class="urdu">(پورٹل پاس ورڈ)</span> <span class="text-secondary small fw-normal"><span class="urdu">(گاہک لاگ ان کے لیے)</span></span></label>
                        <input type="password" class="form-control" name="password" placeholder="Leave blank to skip <span class="urdu">(خالی چھوڑیں)</span>">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Client <span class="urdu">(گاہک محفوظ)</span></button>
            <a href="{{ route('clients.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
