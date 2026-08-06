@extends('layouts.admin')

@section('title', 'Enquiry Details <span class="urdu">(انکوائری کی تفصیلات)</span>')

@section('content')
<div class="page-header">
    <h3>Enquiry <span class="urdu">(انکوائری)</span></h3>
    <div class="page-header-sub">
        <span class="badge {{ $contact->read_at ? 'status-completed' : 'status-pending' }}">
            {{ $contact->read_at ? 'Read' : 'New' }}
        </span>
    </div>
    <div class="action-btns">
        @if($contact->property)
            <a href="{{ route('properties.show', $contact->property) }}" class="btn btn-outline-secondary">
                <i class="ti ti-building"></i> <span class="urdu">(جائیداد دیکھیں)</span>
            </a>
        @endif
        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> <span class="urdu">(واپس)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-message-report me-1"></i> Message <span class="urdu">(پیغام)</span></h5>
            </div>
            <div class="card-body">
                <p class="text-secondary" style="line-height:1.8;white-space:pre-wrap;">{{ $contact->message }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-user me-1"></i> Contact Information <span class="urdu">(رابطے کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr>
                            <th>Name <span class="urdu">(نام)</span></th>
                            <td class="fw-semibold">{{ $contact->name }}</td>
                        </tr>
                        <tr>
                            <th>Email <span class="urdu">(ای میل)</span></th>
                            <td><a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Phone <span class="urdu">(فون)</span></th>
                            <td>
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" class="text-decoration-none">{{ $contact->phone }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Property <span class="urdu">(جائیداد)</span></th>
                            <td>{{ $contact->property_title ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Subject <span class="urdu">(موضوع)</span></th>
                            <td>{{ $contact->subject ?? 'Contact Message' }}</td>
                        </tr>
                        <tr>
                            <th>Received <span class="urdu">(موصول ہوا)</span></th>
                            <td>{{ $contact->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject ?? 'Your enquiry' }}" class="btn btn-dark btn-sm">
                        <i class="ti ti-mail"></i> Reply
                    </a>
                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this enquiry?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="ti ti-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
