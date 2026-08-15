@extends('layouts.admin')

@section('title', 'Deals <span class="urdu">(ڈیلز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Deals <span class="urdu">(ڈیلز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Deals <span class="urdu">(ڈیلز)</span></h3>
        <div class="page-header-sub">{{ $deals->total() }} <span class="urdu">(کل ڈیلز)</span></div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('deals.export', request()->query()) }}" class="btn btn-outline-dark">
            <i class="ti ti-download"></i> Export CSV
        </a>
        <a href="{{ route('deals.export-excel', request()->query()) }}" class="btn btn-outline-success">
            <i class="ti ti-file-spreadsheet"></i> Export Excel <span class="urdu">(اکسل برآمد)</span>
        </a>
        <a href="{{ route('deals.trash') }}" class="btn btn-outline-danger">
            <i class="ti ti-trash"></i> Trash
        </a>
        <a href="{{ route('deals.create') }}" class="btn btn-dark">
            <i class="ti ti-plus"></i> <span class="urdu">(ڈیل شامل کریں)</span>
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('deals.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search deal #, property, buyer, seller..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['sale', 'rent', 'lease'] as $t)
                        <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="lead_source" class="form-select form-select-sm">
                    <option value="">All Sources</option>
                    @foreach($leadSources ?? [] as $key => $label)
                        <option value="{{ $key }}" {{ request('lead_source') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 col-sm-6">
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="ti ti-search"></i> Filter</button>
            </div>
            @if(request()->hasAny(['search', 'status', 'type', 'lead_source']))
            <div class="col-md-1 col-sm-6">
                <a href="{{ route('deals.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="ti ti-x"></i> Clear</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                    <th>Type <span class="urdu">(قسم)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Source <span class="urdu">(ذریعہ)</span></th>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Buyer <span class="urdu">(خریدار)</span></th>
                    <th class="d-none d-md-table-cell">Seller <span class="urdu">(فروخت کنندہ)</span></th>
                    <th class="d-none d-md-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th>Sale Price <span class="urdu">(فروخت قیمت)</span></th>
                    <th class="d-none d-md-table-cell">Commission <span class="urdu">(کمیشن)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                <tr>
                    <td class="fw-semibold">{{ $deal->deal_number }}</td>
                    <td>{{ ucfirst($deal->type ?? '-') }}</td>
                    <td>
                        @php $sc = \App\Helpers\Status::classes('deal'); @endphp
                        <span class="badge {{ $sc[$deal->status] ?? 'status-pending' }}">{{ ucfirst(str_replace('_', ' ', $deal->status ?? 'pending')) }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">{{ \App\Helpers\Status::leadSourceLabel($deal->lead_source) }}</td>
                    <td>{{ $deal->property->title ?? '-' }}</td>
                    <td>{{ $deal->buyer->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->seller->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->agent->name ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($deal->sale_price, 0) }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->commission_amount ? number_format($deal->commission_amount, 0) : ($deal->commission_percentage ? $deal->commission_percentage . '%' : '-') }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('deals.show', $deal) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('deals.edit', $deal) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="ti ti-handshake"></i>
                            <p>No deals found. <span class="urdu">(کوئی ڈیل نہیں ملی)</span></p>
                            <a href="{{ route('deals.create') }}" class="text-decoration-none fw-medium"><span class="urdu">(اپنی پہلی ڈیل شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deals->hasPages())
    <div class="p-3 border-top">
        {{ $deals->links() }}
    </div>
    @endif
</div>
@endsection
