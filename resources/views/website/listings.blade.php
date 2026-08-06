@extends('layouts.website')

@section('title', 'Property Listings — ' . config('app.name', 'Skyline Real Estate'))
@section('meta_description', 'Browse available houses, flats, plots, and commercial properties for sale and rent in Pakistan. Filter by city, type, and budget.')

@section('content')
<section class="page-hero-creative" data-hero-label="LISTINGS">
    <div class="container position-relative">
        <div class="mb-3">
            <span class="icon-box-circle glow-pulse"><i class="ti ti-building"></i></span>
        </div>
        <h1 class="anim-fade-up anim-delay-1">Property <span class="text-amber">Listings</span></h1>
        <p class="lead anim-fade-up anim-delay-2">Browse our curated selection of premium properties</p>
    </div>
</section>

<section class="section-dark">
    <div class="container">
        {{-- Quick filter chips --}}
        <div class="filter-chips anim-fade-up">
            <a href="{{ route('website.properties') }}" class="filter-chip {{ !request('type') ? 'active' : '' }}">
                <i class="ti ti-layout-grid"></i> All
            </a>
            @foreach($types as $type)
                <a href="{{ route('website.properties', array_merge(request()->except('type','page'), ['type' => $type])) }}" class="filter-chip {{ request('type') === $type ? 'active' : '' }}">
                    @if($type === 'house')<i class="ti ti-home"></i>
                    @elseif($type === 'flat')<i class="ti ti-building"></i>
                    @elseif($type === 'plot')<i class="ti ti-map"></i>
                    @elseif($type === 'commercial')<i class="ti ti-building-store"></i>
                    @elseif($type === 'farmhouse')<i class="ti ti-tree"></i>
                    @elseif($type === 'penthouse')<i class="ti ti-building-skyscraper"></i>
                    @else<i class="ti ti-circle"></i>
                    @endif
                    {{ ucfirst($type) }}
                </a>
            @endforeach
            @if(request('transaction_type'))
                <a href="{{ route('website.properties', array_merge(request()->except('transaction_type','page'))) }}" class="filter-chip active">
                    <i class="ti ti-x"></i> {{ ucfirst(request('transaction_type')) }}
                </a>
            @endif
        </div>

        <form method="GET" id="filterForm">
            <div class="row g-4">
                {{-- Sidebar Filters --}}
                <div class="col-lg-3">
                    <div class="filter-sidebar-creative anim-fade-up">
                        <h5><i class="ti ti-sliders me-1"></i> Filters</h5>

                        <div class="filter-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Keyword, area..." value="{{ request('search') }}">
                        </div>

                        <div class="filter-group">
                            <label>City</label>
                            <select name="city" class="form-select">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Property Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Transaction</label>
                            <select name="transaction_type" class="form-select">
                                <option value="">All</option>
                                <option value="sale" {{ request('transaction_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ request('transaction_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range (Rs.)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Bedrooms</label>
                            <select name="bedrooms" class="form-select">
                                <option value="">Any</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                @endfor
                            </select>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-amber"><i class="ti ti-search me-1"></i> Apply Filters</button>
                            <a href="{{ route('website.properties') }}" class="btn btn-outline-amber btn-sm">Clear All</a>
                        </div>
                    </div>
                </div>

                {{-- Property Grid --}}
                <div class="col-lg-9">
                    <div class="results-bar anim-fade-up">
                        <span class="results-count"><strong>{{ $properties->total() }}</strong> properties found</span>
                        @if(request()->hasAny(['search','city','type','min_price','max_price','bedrooms','transaction_type']))
                            <div class="d-flex gap-1 flex-wrap">
                                @if(request('search'))
                                    <span class="filter-chip active" style="pointer-events:none;font-size:0.75rem;">"{{ request('search') }}"</span>
                                @endif
                                @if(request('city'))
                                    <span class="filter-chip active" style="pointer-events:none;font-size:0.75rem;">{{ request('city') }}</span>
                                @endif
                                @if(request('type'))
                                    <span class="filter-chip active" style="pointer-events:none;font-size:0.75rem;">{{ ucfirst(request('type')) }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="row g-4">
                        @forelse($properties as $idx => $property)
                        <div class="col-md-6 col-xl-4 anim-fade-up anim-delay-{{ ($idx % 3) + 1 }}">
                            <div class="property-card-creative h-100">
                                <div class="card-img-wrap">
                                    @if($property->primaryMedia)
                                        <img src="{{ asset('storage/' . $property->primaryMedia->file_path) }}" alt="{{ $property->title }}">
                                    @else
                                        <div class="property-img-placeholder" style="height:100%;"><i class="ti ti-building"></i></div>
                                    @endif
                                    <div class="img-overlay"></div>
                                    <div class="price-badge">Rs. {{ number_format($property->price, 0) }}</div>
                                    <span class="status-pill badge badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                                </div>
                                <div class="card-body">
                                    <h6>{{ $property->title }}</h6>
                                    <div class="card-loc"><i class="ti ti-map-pin"></i>{{ $property->city ?? '' }}{{ $property->location_address ? ', ' . $property->location_address : '' }}</div>
                                    <div class="card-specs">
                                        @if($property->bedrooms)<span><i class="ti ti-bed"></i>{{ $property->bedrooms }} Bed</span>@endif
                                        @if($property->bathrooms)<span><i class="ti ti-bath"></i>{{ $property->bathrooms }} Bath</span>@endif
                                        @if($property->plot_size)<span><i class="ti ti-ruler-2"></i>{{ number_format($property->plot_size, 0) }} {{ $property->plot_size_unit ?? 'sqft' }}</span>@endif
                                    </div>
                                </div>
                                <div class="card-cta">
                                    <a href="{{ route('website.property', $property) }}" class="btn btn-outline-amber btn-sm w-100">View Details <i class="ti ti-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="glass-card-static text-center py-5">
                                <div class="icon-box-circle mx-auto mb-3" style="width:72px;height:72px;font-size:2rem;"><i class="ti ti-search"></i></div>
                                <h4 class="mt-3">No Properties Found</h4>
                                <p class="text-secondary mx-auto" style="max-width:400px;">No properties match your current filters. Try adjusting your search criteria or browse all listings.</p>
                                <a href="{{ route('website.properties') }}" class="btn btn-amber mt-3"><i class="ti ti-refresh me-1"></i> Clear Filters</a>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    @if($properties->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $properties->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
