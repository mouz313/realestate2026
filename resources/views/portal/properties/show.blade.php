@extends('portal.layouts.app')

@section('title', $property->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $property->title }}</h4>
    <a href="{{ route('portal.properties') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        @if($property->media->count() > 0)
        <div class="card shadow-sm mb-3">
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($property->media as $k => $media)
                    <div class="carousel-item {{ $k == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $media->file_path) }}" class="d-block w-100" alt="{{ $media->caption ?? $property->title }}" style="height:400px;object-fit:cover;">
                    </div>
                    @endforeach
                </div>
                @if($property->media->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>
        </div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Description</h5>
                <p class="mb-0">{{ $property->description ?? 'No description available.' }}</p>
            </div>
        </div>

        @if($property->features && count($property->features) > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Features</h5>
                <div class="row">
                    @foreach($property->features as $feature)
                    <div class="col-md-4 mb-1">
                        <i class="ti ti-check text-success me-1"></i> {{ $feature }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Property Details</h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-secondary">Code</td>
                        <td class="fw-semibold">{{ $property->property_code }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Status</td>
                        <td>
                            @php
                                $ps = ['available' => 'success', 'pending' => 'warning', 'sold' => 'danger', 'rented' => 'info', 'leased' => 'info', 'off_market' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $ps[$property->status] ?? 'secondary' }}">{{ ucfirst($property->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Type</td>
                        <td>{{ ucfirst($property->type) }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Transaction</td>
                        <td>{{ ucfirst($property->transaction_type) }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Price</td>
                        <td class="fw-bold fs-5">{{ number_format($property->price, 2) }}</td>
                    </tr>
                    @if($property->price_per_sqft)
                    <tr>
                        <td class="text-secondary">Price/sqft</td>
                        <td>{{ number_format($property->price_per_sqft, 2) }}</td>
                    </tr>
                    @endif
                    <tr><td colspan="2"><hr class="my-1"></td></tr>
                    <tr>
                        <td class="text-secondary">City</td>
                        <td>{{ $property->city ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Sector</td>
                        <td>{{ $property->sector_town ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Block</td>
                        <td>{{ $property->block ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Address</td>
                        <td>{{ $property->location_address ?? '-' }}</td>
                    </tr>
                    <tr><td colspan="2"><hr class="my-1"></td></tr>
                    @if($property->plot_size)
                    <tr>
                        <td class="text-secondary">Plot Size</td>
                        <td>{{ $property->plot_size }} {{ $property->plot_size_unit ?? 'sqft' }}</td>
                    </tr>
                    @endif
                    @if($property->bedrooms)
                    <tr>
                        <td class="text-secondary">Bedrooms</td>
                        <td>{{ $property->bedrooms }}</td>
                    </tr>
                    @endif
                    @if($property->bathrooms)
                    <tr>
                        <td class="text-secondary">Bathrooms</td>
                        <td>{{ $property->bathrooms }}</td>
                    </tr>
                    @endif
                    @if($property->floors)
                    <tr>
                        <td class="text-secondary">Floors</td>
                        <td>{{ $property->floors }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-secondary">Furnished</td>
                        <td>{{ $property->furnished ? 'Yes' : 'No' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($property->owner)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Owner</h5>
                <p class="mb-1 fw-semibold">{{ $property->owner->name }}</p>
                @if($property->owner->phone)
                <p class="mb-0 small text-secondary"><i class="ti ti-phone"></i> {{ $property->owner->phone }}</p>
                @endif
                @if($property->owner->email)
                <p class="mb-0 small text-secondary"><i class="ti ti-mail"></i> {{ $property->owner->email }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($property->assignedAgent)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Agent</h5>
                <p class="mb-1 fw-semibold">{{ $property->assignedAgent->name }}</p>
                @if($property->assignedAgent->phone)
                <p class="mb-0 small text-secondary"><i class="ti ti-phone"></i> {{ $property->assignedAgent->phone }}</p>
                @endif
                @if($property->assignedAgent->email)
                <p class="mb-0 small text-secondary"><i class="ti ti-mail"></i> {{ $property->assignedAgent->email }}</p>
                @endif
            </div>
        </div>
        @endif

        <a href="{{ route('portal.visits.create', ['property_id' => $property->id]) }}" class="btn btn-dark w-100">
            <i class="ti ti-calendar-clock me-1"></i> Schedule Visit
        </a>

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['business_phone'] ?? '') }}?text={{ urlencode('Hello! I am interested in: ' . $property->property_code . ' - ' . $property->title) }}" target="_blank" class="btn btn-success w-100 mt-2">
            <i class="ti ti-brand-whatsapp"></i> Inquiry via WhatsApp
        </a>
    </div>
</div>
@endsection
