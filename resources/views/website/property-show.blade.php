@extends('layouts.website')

@section('title', $property->title . ' — ' . config('app.name', 'Skyline Real Estate'))
@section('meta_description', Str::limit(strip_tags($property->description ?? ($property->title . ' ' . $property->location_address . ' ' . $property->city . ' for ' . $property->transaction_type)), 160))
@section('canonical', route('website.property', $property))
@section('og_type', 'product')
@section('og_image', $property->media->sortByDesc('is_primary')->first() ? asset('storage/' . $property->media->sortByDesc('is_primary')->first()->file_path) : asset('assets/img/og-default.jpg'))

@section('content')
@php $primaryImg = $property->media->sortByDesc('is_primary')->first(); @endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "RealEstateListing",
  "name": {{ json_encode($property->title) }},
  "description": {{ json_encode(Str::limit(strip_tags($property->description ?? ''), 300)) }},
  "url": {{ json_encode(route('website.property', $property)) }},
  "image": {{ json_encode($primaryImg ? asset('storage/' . $primaryImg->file_path) : null) }},
  "offers": {
    "@type": "Offer",
    "price": {{ json_encode((string) $property->price) }},
    "priceCurrency": {{ json_encode($property->currency ?? 'PKR') }}
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": {{ json_encode($property->location_address) }},
    "addressLocality": {{ json_encode($property->city) }},
    "addressRegion": {{ json_encode($property->sector_town) }}
  }
  @if($property->latitude && $property->longitude)
  ,"geo": {
    "@type": "GeoCoordinates",
    "latitude": {{ json_encode((string) $property->latitude) }},
    "longitude": {{ json_encode((string) $property->longitude) }}
  }
  @endif
}
</script>
<section class="page-hero" style="padding:5rem 0 2rem;">
    <div class="container position-relative">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-secondary">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.properties') }}" class="text-decoration-none text-secondary">Listings</a></li>
                <li class="breadcrumb-item active text-amber">{{ $property->title }}</li>
            </ol>
        </nav>
    </div>
</section>

<section class="section-dark pt-0">
    <div class="container">
        <div class="row g-4">
            {{-- Gallery --}}
            <div class="col-lg-8">
                <div class="property-detail-gallery">
                    @php $images = $property->media->sortByDesc('is_primary')->values(); @endphp
                    @if($images->count())
                    <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($images as $idx => $img)
                                <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner rounded">
                            @foreach($images as $idx => $img)
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $img->file_path) }}" class="d-block w-100" alt="{{ $img->caption ?? $property->title }}">
                            </div>
                            @endforeach
                        </div>
                        @if($images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="property-img-placeholder rounded" style="height:450px;">
                        <i class="ti ti-building" style="font-size:4rem;"></i>
                    </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mt-4">
                    <h4>About This Property</h4>
                    <p class="text-secondary" style="line-height:1.8;">{{ $property->description ?? 'No description available for this property.' }}</p>
                </div>

                {{-- Specs Grid --}}
                <div class="mt-4">
                    <h4>Property Details</h4>
                    <div class="detail-spec-grid mt-3">
                        @if($property->type)
                        <div class="detail-spec-item">
                            <i class="ti ti-home"></i>
                            <div class="spec-label">Type</div>
                            <div class="spec-value">{{ ucfirst($property->type) }}</div>
                        </div>
                        @endif
                        @if($property->bedrooms)
                        <div class="detail-spec-item">
                            <i class="ti ti-bed"></i>
                            <div class="spec-label">Bedrooms</div>
                            <div class="spec-value">{{ $property->bedrooms }}</div>
                        </div>
                        @endif
                        @if($property->bathrooms)
                        <div class="detail-spec-item">
                            <i class="ti ti-bath"></i>
                            <div class="spec-label">Bathrooms</div>
                            <div class="spec-value">{{ $property->bathrooms }}</div>
                        </div>
                        @endif
                        @if($property->kitchens)
                        <div class="detail-spec-item">
                            <i class="ti ti-cookie"></i>
                            <div class="spec-label">Kitchens</div>
                            <div class="spec-value">{{ $property->kitchens }}</div>
                        </div>
                        @endif
                        @if($property->plot_size)
                        <div class="detail-spec-item">
                            <i class="ti ti-ruler-2"></i>
                            <div class="spec-label">Plot Size</div>
                            <div class="spec-value">{{ number_format($property->plot_size, 0) }} {{ $property->plot_size_unit ?? 'sqft' }}</div>
                        </div>
                        @endif
                        @if($property->covered_area)
                        <div class="detail-spec-item">
                            <i class="ti ti-layout-grid"></i>
                            <div class="spec-label">Covered Area</div>
                            <div class="spec-value">{{ number_format($property->covered_area, 0) }} {{ $property->covered_area_unit ?? 'sqft' }}</div>
                        </div>
                        @endif
                        @if($property->parking_spaces)
                        <div class="detail-spec-item">
                            <i class="ti ti-car"></i>
                            <div class="spec-label">Parking</div>
                            <div class="spec-value">{{ $property->parking_spaces }} Cars</div>
                        </div>
                        @endif
                        @if($property->furnished !== null)
                        <div class="detail-spec-item">
                            <i class="ti ti-armchair"></i>
                            <div class="spec-label">Furnished</div>
                            <div class="spec-value">{{ $property->furnished ? 'Yes' : 'No' }}</div>
                        </div>
                        @endif
                        @if($property->possession_status)
                        <div class="detail-spec-item">
                            <i class="ti ti-key"></i>
                            <div class="spec-label">Possession</div>
                            <div class="spec-value">{{ ucfirst($property->possession_status) }}</div>
                        </div>
                        @endif
                        @if($property->floors)
                        <div class="detail-spec-item">
                            <i class="ti ti-stack"></i>
                            <div class="spec-label">Floors</div>
                            <div class="spec-value">{{ $property->floors }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Features --}}
                @if($property->features && count($property->features))
                <div class="mt-4">
                    <h4>Features</h4>
                    <div class="row g-2 mt-2">
                        @foreach($property->features as $feature)
                        <div class="col-6 col-md-4">
                            <span class="d-block py-1 px-2 rounded" style="background:rgba(212,162,78,0.06); font-size:0.85rem;">
                                <i class="ti ti-check text-amber me-1"></i> {{ $feature }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Location --}}
                @if($property->location_address)
                <div class="mt-4">
                    <h4>Location</h4>
                    <p class="text-secondary"><i class="ti ti-map-pin text-amber me-1"></i> {{ $property->location_address }}{{ $property->city ? ', ' . $property->city : '' }}{{ $property->sector_town ? ', ' . $property->sector_town : '' }}</p>
                    @php
                        $mapQuery = $property->latitude && $property->longitude
                            ? $property->latitude.','.$property->longitude
                            : urlencode(trim($property->location_address.' '.$property->city));
                    @endphp
                    <div class="rounded overflow-hidden mt-3" style="border:1px solid rgba(212,162,78,0.15);">
                        <iframe src="https://www.google.com/maps?q={{ $mapQuery }}&z=14&output=embed" width="100%" height="320" style="border:0;display:block;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Price Info Box --}}
                <div class="property-info-box mb-4">
                    <div class="info-price">Rs. {{ number_format($property->price, 0) }}</div>
                    <div class="info-spec mt-2">
                        @if($property->bedrooms)
                            <span class="me-3"><i class="ti ti-bed"></i> {{ $property->bedrooms }} Bedrooms</span>
                        @endif
                        @if($property->bathrooms)
                            <span class="me-3"><i class="ti ti-bath"></i> {{ $property->bathrooms }} Bathrooms</span>
                        @endif
                    </div>
                    <div class="info-spec mt-1">
                        @if($property->plot_size)
                            <span><i class="ti ti-ruler-2"></i> {{ number_format($property->plot_size, 0) }} {{ $property->plot_size_unit ?? 'sqft' }}</span>
                        @endif
                        @if($property->type)
                            <span class="ms-3"><i class="ti ti-home"></i> {{ ucfirst($property->type) }}</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                        @if($property->transaction_type)
                            <span class="badge" style="background:rgba(212,162,78,0.15);color:var(--sky-amber);">{{ ucfirst($property->transaction_type) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Contact Agent --}}
                <div class="filter-sidebar mb-4">
                    <h5><i class="ti ti-user me-1"></i> Contact Agent</h5>
                    @if($property->assignedAgent)
                    <div class="d-flex align-items-center mb-3">
                        @if($property->assignedAgent->photo)
                            <img src="{{ asset('storage/' . $property->assignedAgent->photo) }}" alt="{{ $property->assignedAgent->name }}" class="rounded-circle me-3" style="width:50px;height:50px;object-fit:cover;">
                        @else
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;background:rgba(212,162,78,0.15);">
                                <i class="ti ti-user text-amber"></i>
                            </div>
                        @endif
                        <div>
                            <div class="fw-semibold">{{ $property->assignedAgent->name }}</div>
                            <small class="text-secondary">Real Estate Agent</small>
                        </div>
                    </div>
                    @if($property->assignedAgent->whatsapp)
                        <a href="https://wa.me/{{ ltrim($property->assignedAgent->whatsapp, '+') }}" class="btn btn-amber w-100 mb-2" target="_blank">
                            <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp
                        </a>
                    @endif
                    @if($property->assignedAgent->phone)
                        <a href="tel:{{ $property->assignedAgent->phone }}" class="btn btn-outline-amber w-100">
                            <i class="ti ti-phone me-1"></i> Call Now
                        </a>
                    @endif
                    @else
                    <a href="https://wa.me/{{ ltrim($contactInfo['phone'] ?? '923001234567', '+') }}" class="btn btn-amber w-100 mb-2" target="_blank">
                        <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp Us
                    </a>
                    <a href="tel:{{ $contactInfo['phone'] ?? '+923001234567' }}" class="btn btn-outline-amber w-100">
                        <i class="ti ti-phone me-1"></i> Call Now
                    </a>
                    @endif
                </div>

                {{-- Enquiry Form --}}
                <div class="filter-sidebar mb-4">
                    <h5><i class="ti ti-message-circle me-1"></i> Request Details</h5>
                    <form action="{{ route('website.property.enquiry', $property) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <input type="email" name="email" class="form-control" placeholder="Your email" value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone (optional)" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="I am interested in this property..." required>{{ old('message') }}</textarea>
                            @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-amber w-100">
                            <i class="ti ti-send me-1"></i> Send Enquiry
                        </button>
                    </form>
                </div>

                {{-- Quick Info --}}
                <div class="filter-sidebar">
                    <h5><i class="ti ti-info-circle me-1"></i> Quick Info</h5>
                    <ul class="list-unstyled mb-0">
                        @if($property->property_code)
                        <li class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(212,162,78,0.1) !important;">
                            <span class="text-secondary">Property Code</span>
                            <span class="fw-semibold">{{ $property->property_code }}</span>
                        </li>
                        @endif
                        @if($property->listed_date)
                        <li class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(212,162,78,0.1) !important;">
                            <span class="text-secondary">Listed</span>
                            <span>{{ $property->listed_date->format('d M Y') }}</span>
                        </li>
                        @endif
                        @if($property->possession_year)
                        <li class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(212,162,78,0.1) !important;">
                            <span class="text-secondary">Possession Year</span>
                            <span>{{ $property->possession_year }}</span>
                        </li>
                        @endif
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-secondary">Views</span>
                            <span>{{ $property->views_count ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Similar Properties --}}
        @if($related->count())
        <div class="mt-5">
            <div class="section-heading text-start">
                <h2>Similar <span class="text-amber">Properties</span></h2>
            </div>
            <div class="row g-4">
                @foreach($related as $rel)
                <div class="col-md-6 col-lg-3">
                    <div class="property-card h-100">
                        @if($rel->primaryMedia)
                            <img src="{{ asset('storage/' . $rel->primaryMedia->file_path) }}" class="card-img-top" alt="{{ $rel->title }}">
                        @else
                            <div class="property-img-placeholder">
                                <i class="ti ti-building"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $rel->title }}</h6>
                            <p class="card-text small text-secondary"><i class="ti ti-map-pin"></i> {{ $rel->city ?? '' }}</p>
                            <div class="price">Rs. {{ number_format($rel->price, 0) }}</div>
                            <a href="{{ route('website.property', $rel) }}" class="btn btn-outline-amber btn-sm w-100 mt-2">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Sticky Mobile CTA --}}
<div class="sticky-cta">
    <a href="https://wa.me/{{ ltrim($contactInfo['phone'] ?? '923001234567', '+') }}" class="btn btn-amber flex-fill" target="_blank">
        <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp
    </a>
    <a href="tel:{{ $contactInfo['phone'] ?? '+923001234567' }}" class="btn btn-outline-amber flex-fill">
        <i class="ti ti-phone me-1"></i> Call Now
    </a>
</div>
@endsection
