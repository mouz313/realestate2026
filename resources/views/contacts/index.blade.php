@extends('layouts.admin')

@section('title', 'Enquiries <span class="urdu">(انکوائریاں)</span>')

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Enquiries <span class="urdu">(انکوائریاں)</span></h3>
        <div class="page-header-sub">{{ $contacts->total() }} <span class="urdu">(کل انکوائریاں)</span></div>
    </div>
    <a href="{{ route('contacts.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Enquiry <span class="urdu">(نئی انکوائری)</span>
    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Name, email, phone, property...">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">Source</label>
                <select name="lead_source" class="form-select form-select-sm">
                    <option value="">All Sources</option>
                    @foreach($leadSources ?? [] as $key => $label)
                        <option value="{{ $key }}" {{ request('lead_source') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">Property Type</label>
                <select name="property_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($propertyTypes ?? [] as $key => $label)
                        <option value="{{ $key }}" {{ request('property_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small">Purpose</label>
                <select name="purpose" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($purposes ?? [] as $key => $label)
                        <option value="{{ $key }}" {{ request('purpose') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100"><i class="ti ti-filter"></i> Filter</button>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th class="d-none d-sm-table-cell">Contact <span class="urdu">(رابطہ)</span></th>
                    <th class="d-none d-lg-table-cell">Source <span class="urdu">(ذریعہ)</span></th>
                    <th class="d-none d-lg-table-cell">Type <span class="urdu">(قسم)</span></th>
                    <th class="d-none d-md-table-cell">Purpose <span class="urdu">(مقصد)</span></th>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Subject <span class="urdu">(موضوع)</span></th>
                    <th class="d-none d-md-table-cell">Date <span class="urdu">(تاریخ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr class="{{ $contact->read_at ? '' : 'table-active' }}">
                    <td class="fw-semibold">
                        {{ $contact->name }}
                        @if(! $contact->read_at)
                            <span class="badge bg-warning text-dark ms-1">New</span>
                        @endif
                    </td>
                    <td class="text-secondary d-none d-sm-table-cell">
                        <div>{{ $contact->email }}</div>
                        @if($contact->phone)
                            <small>{{ $contact->phone }}</small>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge status-info">{{ \App\Helpers\Status::leadSourceLabel($contact->lead_source) }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($contact->property_type)
                            <span class="badge status-info">{{ \App\Helpers\Status::propertyTypeLabel($contact->property_type) }}</span>
                        @else
                            <span class="text-secondary">-</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($contact->purpose)
                            <span class="badge {{ $contact->purpose === 'rent' ? 'status-pending' : 'status-completed' }}">{{ \App\Helpers\Status::purposeLabel($contact->purpose) }}</span>
                        @else
                            <span class="text-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->property)
                            <a href="{{ route('properties.show', $contact->property) }}" class="text-decoration-none fw-medium">{{ $contact->property_title ?? $contact->property->title }}</a>
                        @else
                            {{ $contact->property_title ?? '-' }}
                        @endif
                    </td>
                    <td class="text-secondary">{{ $contact->subject ?? 'Contact Message' }}</td>
                    <td class="text-secondary d-none d-md-table-cell">{{ $contact->created_at->format('d M Y H:i') }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="{{ route('contacts.show', $contact) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this enquiry?')">
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
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="ti ti-message-report"></i>
                            <p>No enquiries yet. <span class="urdu">(ابھی تک کوئی انکوائری نہیں)</span></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
    <div class="p-3 border-top">
        {{ $contacts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
