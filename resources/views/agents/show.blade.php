@extends('layouts.admin')

@section('title', 'Agent Details')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('agents.index') }}" class="text-decoration-none">Agents</a></li>
        <li class="breadcrumb-item active">{{ $agent->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>{{ $agent->name }}</h3>
    <div class="page-header-sub">Agent Details</div>
    <div class="action-btns">
        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> Edit Agent
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-user me-1"></i> Agent Information</h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $agent->name }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ $agent->role ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $agent->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>WhatsApp</th>
                        <td>{{ $agent->whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $agent->email }}" class="text-decoration-none">{{ $agent->email ?? '-' }}</a></td>
                    </tr>
                    <tr>
                        <th>CNIC</th>
                        <td>{{ $agent->cnic ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Commission Rate</th>
                        <td>{{ $agent->commission_rate ? $agent->commission_rate . '%' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge status-{{ $agent->status ?? 'inactive' }}">{{ ucfirst($agent->status ?? 'inactive') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $agent->type ?? '-')) }}</td>
                    </tr>
                    <tr>
                        <th>Join Date</th>
                        <td>{{ $agent->join_date ? $agent->join_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>License Number</th>
                        <td>{{ $agent->license_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $agent->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Bio / About</th>
                        <td>{{ $agent->bio ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Experience</th>
                        <td>{{ $agent->experience_years ? $agent->experience_years . ' years' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Languages</th>
                        <td>{{ $agent->languages ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Specializations</th>
                        <td>
                            @if($agent->specializations)
                                @php $specs = is_array($agent->specializations) ? $agent->specializations : json_decode($agent->specializations, true); @endphp
                                @foreach($specs as $s)
                                <span class="badge bg-light text-dark me-1">{{ $s }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td>@if($agent->website)<a href="{{ $agent->website }}" target="_blank" class="text-decoration-none">{{ $agent->website }}</a>@else-@endif</td>
                    </tr>
                    <tr>
                        <th>Social Media</th>
                        <td>
                            <div class="d-flex gap-2">
                                @if($agent->facebook)<a href="{{ $agent->facebook }}" target="_blank" class="text-decoration-none" title="Facebook"><i class="ti ti-brand-facebook fs-5 text-primary"></i></a>@endif
                                @if($agent->twitter)<a href="{{ $agent->twitter }}" target="_blank" class="text-decoration-none" title="Twitter"><i class="ti ti-brand-twitter fs-5"></i></a>@endif
                                @if($agent->linkedin)<a href="{{ $agent->linkedin }}" target="_blank" class="text-decoration-none" title="LinkedIn"><i class="ti ti-brand-linkedin fs-5 text-primary"></i></a>@endif
                                @if($agent->instagram)<a href="{{ $agent->instagram }}" target="_blank" class="text-decoration-none" title="Instagram"><i class="ti ti-brand-instagram fs-5 text-danger"></i></a>@endif
                                @if(!$agent->facebook && !$agent->twitter && !$agent->linkedin && !$agent->instagram)-@endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $agent->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-trending-up me-1"></i> Performance Summary</h5>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-6">
                        <div class="fs-3 fw-bold">{{ $agent->deals->count() }}</div>
                        <div class="text-secondary small">Total Deals</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-3 fw-bold text-success">{{ number_format($agent->commissions->sum('amount'), 2) }}</div>
                        <div class="text-secondary small">Total Commission</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="ti ti-file-description me-1"></i> Recent Deals</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Deal #</th>
                                <th>Property</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agent->deals as $deal)
                            <tr>
                                <td><a href="{{ route('deals.show', $deal) }}" class="text-decoration-none">{{ $deal->deal_number }}</a></td>
                                <td>{{ $deal->property->title ?? '-' }}</td>
                                <td>{{ number_format($deal->sale_price, 2) }}</td>
                                <td>
                                    <span class="badge status-{{ $deal->status ?? 'pending' }}">{{ ucfirst($deal->status ?? 'pending') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="ti ti-file-description"></i><span>No deals yet.</span></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="ti ti-currency-dollar me-1"></i> Commissions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Deal</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agent->commissions as $commission)
                            <tr>
                                <td><a href="{{ route('deals.show', $commission->deal) }}" class="text-decoration-none">{{ $commission->deal->deal_number ?? '#' }}</a></td>
                                <td>{{ number_format($commission->amount, 2) }}</td>
                                <td>
                                    <span class="badge status-{{ $commission->status ?? 'pending' }}">{{ ucfirst($commission->status ?? 'pending') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="ti ti-currency-dollar"></i><span>No commissions yet.</span></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
