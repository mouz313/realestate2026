@extends('layouts.admin')

@section('title', 'Property Details <span class="urdu">(جائیداد کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties.index') }}" class="text-decoration-none">Properties <span class="urdu">(جائیدادیں)</span></a></li>
        <li class="breadcrumb-item active">{{ $property->title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>{{ $property->title }}</h3>
        <div class="page-header-sub"><span class="urdu">(کوڈ)</span>: {{ $property->property_code ?? $property->id }}</div>
    </div>
    <div>
        @php $sc = ['available' => 'status-available', 'sold' => 'status-sold', 'rented' => 'status-rented', 'under_offer' => 'status-under_offer', 'pending' => 'status-pending', 'reserved' => 'status-reserved']; @endphp
        <span class="badge {{ $sc[$property->status] ?? 'status-pending' }}" style="font-size:0.8rem;">{{ ucfirst(str_replace('_', ' ', $property->status ?? 'available')) }}</span>
        <a href="{{ route('properties.edit', $property) }}" class="btn btn-dark ms-2">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم)</span>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-building me-1"></i> Property Information <span class="urdu">(جائیداد کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr><th>Title <span class="urdu">(عنوان)</span></th><td>{{ $property->title }}</td></tr>
                    <tr><th>Type <span class="urdu">(قسم)</span></th><td>{{ ucfirst($property->type ?? '-') }}</td></tr>
                    <tr><th>Transaction Type <span class="urdu">(لین دین کی قسم)</span></th><td>{{ ucfirst($property->transaction_type ?? '-') }}</td></tr>
                    <tr><th>Price <span class="urdu">(قیمت)</span></th><td class="fw-semibold" style="font-size:1.05rem;">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'PKR' }}</td></tr>
                    <tr><th>Price Per Sqft <span class="urdu">(قیمت فی مربع فٹ)</span></th><td>{{ $property->price_per_sqft ? number_format($property->price_per_sqft, 0) : '-' }}</td></tr>
                    <tr><th>Possession <span class="urdu">(قبضہ)</span></th><td>@if($property->possession_status) {{ ucfirst(str_replace('_', ' ', $property->possession_status)) }}@if($property->possession_year) {{ $property->possession_year }}@endif @else - @endif</td></tr>
                    <tr><th>City <span class="urdu">(شہر)</span></th><td>{{ $property->city ?? '-' }}</td></tr>
                    <tr><th>Sector / Town <span class="urdu">(سیکٹر / ٹاؤن)</span></th><td>{{ $property->sector_town ?? '-' }}</td></tr>
                    <tr><th>Block <span class="urdu">(بلاک)</span></th><td>{{ $property->block ?? '-' }}</td></tr>
                    <tr><th>Location <span class="urdu">(مقام)</span></th><td>{{ $property->location_address ?? '-' }}</td></tr>
                    <tr><th>Plot Size <span class="urdu">(پلاٹ کا سائز)</span></th><td>{{ $property->plot_size ? $property->plot_size . ' ' . ($property->plot_size_unit ?? '') : '-' }}</td></tr>
                    <tr><th>Land Area <span class="urdu">(رقبہ)</span></th><td>{{ $property->land_area ?? '-' }}</td></tr>
                    <tr><th>Covered Area <span class="urdu">(تعمیر شدہ رقبہ)</span></th><td>{{ $property->covered_area ? $property->covered_area . ' ' . ($property->covered_area_unit ?? '') : '-' }}</td></tr>
                    <tr><th>Bedrooms / Bathrooms <span class="urdu">(بیڈروم / باتھ روم)</span></th><td>{{ $property->bedrooms ?? '0' }} / {{ $property->bathrooms ?? '0' }}</td></tr>
                    <tr><th>Kitchens / Floors <span class="urdu">(کچن / منزلیں)</span></th><td>{{ $property->kitchens ?? '0' }} / {{ $property->floors ?? '0' }}</td></tr>
                    <tr><th>Floor # <span class="urdu">(منزل نمبر)</span></th><td>{{ $property->floor_number !== null ? $property->floor_number : '-' }} @if($property->total_floors) / {{ $property->total_floors }} <span class="urdu">(منزلیں)</span> @endif</td></tr>
                    <tr><th>Furnished <span class="urdu">(فرنشڈ)</span></th><td>{{ $property->furnished ? 'Yes' : 'No' }}</td></tr>
                    <tr><th>Parking <span class="urdu">(پارکنگ)</span></th><td>{{ $property->parking_spaces ?? '0' }} <span class="urdu">(جگہیں)</span></td></tr>
                    <tr><th>Additional Rooms <span class="urdu">(اضافی کمرے)</span></th><td>@if(!empty($property->additional_rooms)) @foreach($property->additional_rooms as $r) <span class="badge bg-light text-dark me-1"><i class="ti ti-door me-1"></i>{{ $r }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Building Features <span class="urdu">(عمارت کی خصوصیات)</span></th><td>@if(!empty($property->building_features)) @foreach($property->building_features as $f) <span class="badge bg-light text-dark me-1"><i class="ti ti-building-skyscraper me-1"></i>{{ $f }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Community Amenities <span class="urdu">(کمیونٹی سہولیات)</span></th><td>@if(!empty($property->community_amenities)) @foreach($property->community_amenities as $a) <span class="badge bg-light text-dark me-1"><i class="ti ti-users me-1"></i>{{ $a }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Communication <span class="urdu">(مواصلات)</span></th><td>@if(!empty($property->communication_features)) @foreach($property->communication_features as $c) <span class="badge bg-light text-dark me-1"><i class="ti ti-antenna me-1"></i>{{ $c }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Features <span class="urdu">(خصوصیات)</span></th><td>{{ $property->features ?? '-' }}</td></tr>
                    <tr><th>Nearby Landmarks <span class="urdu">(قریبی نشانات)</span></th><td>{{ $property->nearby_landmarks ?? '-' }}</td></tr>
                    <tr><th>Nearby Places <span class="urdu">(قریبی مقامات)</span></th><td>@if(!empty($property->nearby_places)) @foreach($property->nearby_places as $place) <span class="badge bg-light text-dark me-1"><i class="ti ti-map-pin me-1"></i>{{ $place }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Utilities <span class="urdu">(یوٹیلیٹیز)</span></th><td>@if(!empty($property->utilities)) @foreach($property->utilities as $util) <span class="badge bg-light text-dark me-1"><i class="ti ti-bolt me-1"></i>{{ $util }}</span> @endforeach @else - @endif</td></tr>
                    <tr><th>Description <span class="urdu">(وضاحت)</span></th><td>{{ $property->description ?? '-' }}</td></tr>
                    <tr><th>Owner <span class="urdu">(مالک)</span></th><td>@if($property->owner) <a href="{{ route('clients.show', $property->owner) }}" class="text-decoration-none">{{ $property->owner->name }}</a> @else - @endif</td></tr>
                    <tr><th>Agent <span class="urdu">(ایجنٹ)</span></th><td>@if($property->agent) <a href="{{ route('agents.show', $property->agent) }}" class="text-decoration-none">{{ $property->agent->name }}</a> @else - @endif</td></tr>
                    <tr><th>Listed Date <span class="urdu">(تاریخ اجراء)</span></th><td>{{ $property->listed_date ? $property->listed_date->format('d M Y') : '-' }}</td></tr>
                    <tr><th>Expiry Date <span class="urdu">(تاریخ میعاد)</span></th><td>{{ $property->expiry_date ? $property->expiry_date->format('d M Y') : '-' }}</td></tr>
                    <tr><th>Notes <span class="urdu">(نوٹس)</span></th><td>{{ $property->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-photo me-1"></i> Media <span class="urdu">(میڈیا)</span> ({{ $property->media->count() }})</h5>
            </div>
            <div class="card-body">
                @if($property->media->count())
                <div class="row g-2">
                    @foreach($property->media as $media)
                    <div class="col-4 col-md-3">
                        @if($media->type === 'video')
                        <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:100px;">
                            <i class="ti ti-video fs-1 text-secondary"></i>
                        </div>
                        @else
                        <div class="position-relative">
                            <img src="{{ Storage::url($media->file_path) }}" class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;">
                            @if($media->is_primary)
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0" style="font-size:0.55rem;">Primary</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-secondary">
                    <i class="ti ti-photo-plus" style="font-size:2rem;opacity:0.4;"></i>
                    <p class="mt-2 mb-0">No media uploaded. <span class="urdu">(کوئی میڈیا اپ لوڈ نہیں)</span></p>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="ti ti-file-text me-1"></i> Documents <span class="urdu">(دستاویزات)</span></h5>
            </div>
            <div class="card-body text-center py-4 text-secondary">
                <i class="ti ti-file-upload" style="font-size:2rem;opacity:0.4;"></i>
                <p class="mt-2 mb-0"><span class="urdu">(دستاویزات کا سیکشن)</span></p>
                <small><span class="urdu">(یہاں جائیداد کی دستاویزات اپ لوڈ کریں)</span></small>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="ti ti-file-description me-1"></i> Deals <span class="urdu">(ڈیلز)</span></h5>
                <a href="{{ \App\Helpers\WhatsApp::shareLink($settings['business_phone'] ?? '', \App\Helpers\WhatsApp::propertyInquiryMessage($property, $settings)) }}" target="_blank" class="btn btn-success btn-sm">
                    <i class="ti ti-brand-whatsapp"></i> <span class="urdu">(شیئر کریں)</span>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                                <th>Type <span class="urdu">(قسم)</span></th>
                                <th>Price <span class="urdu">(قیمت)</span></th>
                                <th>Status <span class="urdu">(کیفیت)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($property->deals ?? [] as $deal)
                            <tr>
                                <td><a href="{{ route('deals.show', $deal) }}" class="text-decoration-none fw-medium">{{ $deal->deal_number }}</a></td>
                                <td>{{ ucfirst($deal->type) }}</td>
                                <td class="fw-medium">{{ number_format($deal->sale_price, 0) }}</td>
                                <td>
                                    @php $sc = ['pending' => 'status-pending', 'active' => 'status-active', 'completed' => 'status-completed', 'cancelled' => 'status-cancelled']; @endphp
                                    <span class="badge {{ $sc[$deal->status] ?? 'status-pending' }}">{{ ucfirst($deal->status ?? 'pending') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">No deals for this property. <span class="urdu">(اس جائیداد کے لیے کوئی ڈیل نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection