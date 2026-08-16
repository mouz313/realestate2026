@extends('layouts.website')

@section('title', 'Find Your Dream Property — ' . config('app.name', 'Skyline Real Estate'))
@section('meta_description', 'Buy, sell, and rent houses, flats, plots, and commercial properties in Pakistan with ' . config('app.name', 'Skyline Real Estate') . ' — trusted real estate agency.')

@section('content')
{{-- HERO --}}
<section class="sky-hero position-relative" style="overflow:hidden;">
    @if(!empty($sliderImages))
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" style="position:absolute;inset:0;z-index:1;">
        <div class="carousel-inner h-100">
            @foreach($sliderImages as $si => $slide)
            <div class="carousel-item h-100 {{ $si === 0 ? 'active' : '' }}">
                <img src="{{ Storage::url($slide['image']) }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $slide['title'] ?? '' }}">
            </div>
            @endforeach
        </div>
    </div>
    <div style="position:absolute;inset:0;z-index:2;background:linear-gradient(135deg, rgba(26,29,36,.82) 0%, rgba(26,29,36,.68) 100%);"></div>
    @endif

    @for($i = 0; $i < 12; $i++)
        <div class="sky-particle" style="left:{{ rand(5,95) }}%;top:{{ rand(10,80) }}%;animation-delay:{{ $i * 0.5 }}s;animation-duration:{{ rand(4,7) }}s;width:{{ rand(3,6) }}px;height:{{ rand(3,6) }}px;"></div>
    @endfor

    <div class="container position-relative" style="z-index:3;">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8">
                <div class="sky-hero-content">
                    <div class="mb-3 anim-fade-up">
                        <span class="floating-badge">
                            <i class="ti ti-flame"></i> #1 Trusted Real Estate Platform in Pakistan
                        </span>
                    </div>
                    <h1 class="anim-fade-up anim-delay-1" style="line-height:1.1;">
                        Find Your<br>
                        <span class="text-amber">Perfect</span> Place<br>
                        to Live<span class="typing-cursor"></span>
                    </h1>
                    <p class="lead mb-4 anim-fade-up anim-delay-2">Discover premium properties for sale and rent across Pakistan's finest neighborhoods.</p>
                    <div class="d-flex gap-3 flex-wrap anim-fade-up anim-delay-3">
                        <a href="{{ route('website.properties') }}" class="btn btn-amber btn-lg px-4">
                            <i class="ti ti-search me-1"></i> Browse Listings
                        </a>
                        <a href="{{ route('website.contact') }}" class="btn btn-outline-amber btn-lg px-4">
                            <i class="ti ti-phone me-1"></i> Contact Us
                        </a>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap anim-fade-up anim-delay-4">
                        <div class="floating-badge" style="animation-delay:0.2s;">
                            <i class="ti ti-building"></i> {{ number_format($stats['properties'] ?? 0) }}+ Properties
                        </div>
                        <div class="floating-badge" style="animation-delay:0.6s;">
                            <i class="ti ti-users"></i> {{ number_format($stats['clients'] ?? 0) }}+ Clients
                        </div>
                        <div class="floating-badge" style="animation-delay:1s;">
                            <i class="ti ti-star"></i> 4.9 Rating
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <svg class="position-absolute bottom-0 start-0 w-100" style="height:80px;" viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <rect x="0" y="40" width="1440" height="40" fill="#1A1D24"/>
        <rect x="30" y="20" width="45" height="60" fill="#D4A24E" opacity="0.08"/>
        <rect x="34" y="25" width="5" height="5" class="skyline-window" style="animation-delay:0.2s"/><rect x="44" y="25" width="5" height="5" class="skyline-window" style="animation-delay:0.5s"/><rect x="54" y="25" width="5" height="5" class="skyline-window" style="animation-delay:0.8s"/>
        <rect x="34" y="35" width="5" height="5" class="skyline-window" style="animation-delay:1.1s"/><rect x="44" y="35" width="5" height="5" class="skyline-window" style="animation-delay:0.4s"/><rect x="54" y="35" width="5" height="5" class="skyline-window" style="animation-delay:0.7s"/>
        <rect x="100" y="10" width="55" height="70" fill="#D4A24E" opacity="0.08"/>
        <rect x="106" y="16" width="6" height="6" class="skyline-window" style="animation-delay:0.3s"/><rect x="120" y="16" width="6" height="6" class="skyline-window" style="animation-delay:0.6s"/><rect x="134" y="16" width="6" height="6" class="skyline-window" style="animation-delay:1.0s"/>
        <rect x="106" y="28" width="6" height="6" class="skyline-window" style="animation-delay:1.3s"/><rect x="120" y="28" width="6" height="6" class="skyline-window" style="animation-delay:0.2s"/>
        <rect x="250" y="5" width="60" height="75" fill="#D4A24E" opacity="0.08"/>
        <rect x="256" y="11" width="7" height="7" class="skyline-window" style="animation-delay:0.1s"/><rect x="270" y="11" width="7" height="7" class="skyline-window" style="animation-delay:0.7s"/><rect x="284" y="11" width="7" height="7" class="skyline-window" style="animation-delay:1.1s"/>
        <rect x="256" y="24" width="7" height="7" class="skyline-window" style="animation-delay:1.4s"/><rect x="270" y="24" width="7" height="7" class="skyline-window" style="animation-delay:0.3s"/>
        <rect x="540" y="8" width="65" height="72" fill="#D4A24E" opacity="0.08"/>
        <rect x="546" y="14" width="7" height="7" class="skyline-window" style="animation-delay:0.4s"/><rect x="560" y="14" width="7" height="7" class="skyline-window" style="animation-delay:0.9s"/><rect x="574" y="14" width="7" height="7" class="skyline-window" style="animation-delay:1.4s"/><rect x="588" y="14" width="7" height="7" class="skyline-window" style="animation-delay:0.7s"/>
        <rect x="546" y="27" width="7" height="7" class="skyline-window" style="animation-delay:1.1s"/><rect x="560" y="27" width="7" height="7" class="skyline-window" style="animation-delay:0.3s"/>
        <rect x="770" y="12" width="55" height="68" fill="#D4A24E" opacity="0.08"/>
        <rect x="776" y="18" width="6" height="6" class="skyline-window" style="animation-delay:0.2s"/><rect x="790" y="18" width="6" height="6" class="skyline-window" style="animation-delay:0.7s"/><rect x="804" y="18" width="6" height="6" class="skyline-window" style="animation-delay:1.2s"/>
        <rect x="776" y="30" width="6" height="6" class="skyline-window" style="animation-delay:0.5s"/><rect x="790" y="30" width="6" height="6" class="skyline-window" style="animation-delay:1.0s"/>
        <rect x="1060" y="10" width="60" height="70" fill="#D4A24E" opacity="0.08"/>
        <rect x="1066" y="16" width="7" height="7" class="skyline-window" style="animation-delay:0.1s"/><rect x="1080" y="16" width="7" height="7" class="skyline-window" style="animation-delay:0.6s"/><rect x="1094" y="16" width="7" height="7" class="skyline-window" style="animation-delay:1.1s"/>
        <rect x="1066" y="29" width="7" height="7" class="skyline-window" style="animation-delay:1.4s"/><rect x="1080" y="29" width="7" height="7" class="skyline-window" style="animation-delay:0.3s"/>
        <rect x="1280" y="15" width="50" height="65" fill="#D4A24E" opacity="0.08"/>
        <rect x="1286" y="21" width="6" height="6" class="skyline-window" style="animation-delay:0.3s"/><rect x="1300" y="21" width="6" height="6" class="skyline-window" style="animation-delay:0.8s"/><rect x="1314" y="21" width="6" height="6" class="skyline-window" style="animation-delay:1.3s"/>
        <rect x="1286" y="33" width="6" height="6" class="skyline-window" style="animation-delay:0.6s"/><rect x="1300" y="33" width="6" height="6" class="skyline-window" style="animation-delay:1.1s"/>
    </svg>
</section>

{{-- SEARCH BAR --}}
<section class="section-darker" style="padding:2rem 0;position:relative;z-index:10;margin-top:-40px;">
    <div class="container">
        <form action="{{ route('website.properties') }}" method="GET" class="search-glass" style="padding:1.5rem 2rem;">
            <div class="row g-3 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold mb-1" style="color:var(--sky-amber);">Location</label>
                    <input type="text" name="search" class="form-control" placeholder="City, area, or landmark..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold mb-1" style="color:var(--sky-amber);">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="house">House</option>
                        <option value="flat">Flat</option>
                        <option value="plot">Plot</option>
                        <option value="commercial">Commercial</option>
                        <option value="farmhouse">Farmhouse</option>
                        <option value="penthouse">Penthouse</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold mb-1" style="color:var(--sky-amber);">Min Price</label>
                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold mb-1" style="color:var(--sky-amber);">Max Price</label>
                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-amber w-100 py-2">
                        <i class="ti ti-search me-1"></i> Search Properties
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- MARQUEE TICKER --}}
<div class="marquee-wrap">
    <div class="marquee-track">
        @php
            $marqueeItems = [
                ['ti ti-building-house', 'Houses'],
                ['ti ti-building', 'Apartments'],
                ['ti ti-map', 'Plots'],
                ['ti ti-building-store', 'Commercial'],
                ['ti ti-tree', 'Farmhouses'],
                ['ti ti-crown', 'Penthouses'],
                ['ti ti-key', 'Rentals'],
                ['ti ti-certificate', 'Verified'],
            ];
            $allMarqueeItems = array_merge($marqueeItems, $marqueeItems);
        @endphp
        @foreach($allMarqueeItems as $mi)
            <span class="marquee-item"><i class="{{ $mi[0] }}"></i> {{ $mi[1] }}</span>
            <span class="marquee-dot"></span>
        @endforeach
    </div>
</div>

{{-- HOW IT WORKS — JOURNEY PATH --}}
<section class="section-darker" style="padding:5rem 0;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-route"></i></span>
            <h2 class="mt-3">How It <span class="text-amber">Works</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Your journey to the perfect property in four simple steps</p>
        </div>

        <div class="how-journey">
            {{-- Desktop connecting line --}}
            <div class="d-none d-lg-block how-connector-line">
                <div class="line-track"></div>
                <div class="line-fill" id="howLineFill"></div>
                <div class="line-dot" data-step="1"></div>
                <div class="line-dot" data-step="2"></div>
                <div class="line-dot" data-step="3"></div>
                <div class="line-dot" data-step="4"></div>
            </div>

            {{-- Desktop layout --}}
            <div class="d-none d-lg-block">
                <div class="row how-journey-row">
                    @php
                        $steps = [
                            ['ti ti-search', 'Search', 'Browse thousands of verified listings. Filter by location, type, budget, and more to find exactly what you need.'],
                            ['ti ti-calendar-event', 'Book a Visit', 'Schedule a property visit at your convenience. Our agents will arrange everything for a hassle-free experience.'],
                            ['ti ti-message-circle', 'Get Consultation', 'Receive expert market analysis, pricing insights, and personalized guidance from our experienced team.'],
                            ['ti ti-key', 'Close the Deal', 'We handle all legal work, documentation, and negotiations so you can move into your new property stress-free.'],
                        ];
                    @endphp
                    @foreach($steps as $idx => $step)
                    <div class="col-lg-3 reveal" style="transition-delay:{{ $idx * 0.12 }}s;">
                        <div class="how-node">
                            <div class="how-node-card">
                                <span class="how-bg-num">{{ $idx + 1 }}</span>
                                <div class="how-icon-ring">
                                    <i class="{{ $step[0] }}"></i>
                                    <span class="how-step-badge">{{ $idx + 1 }}</span>
                                </div>
                                <h5>{{ $step[1] }}</h5>
                                <p>{{ $step[2] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Mobile layout --}}
            <div class="d-lg-none">
                <div class="how-journey-mobile">
                    @foreach($steps as $idx => $step)
                    <div class="how-node-mobile reveal" style="transition-delay:{{ $idx * 0.1 }}s;">
                        <div class="how-node-card">
                            <div class="how-icon-ring">
                                <i class="{{ $step[0] }}"></i>
                                <span class="how-step-badge">{{ $idx + 1 }}</span>
                            </div>
                            <h5>{{ $step[1] }}</h5>
                            <p>{{ $step[2] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
        </div>
    </div>
</section>

{{-- WHY CHOOSE US --}}
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-shield-check"></i></span>
            <h2 class="mt-3">Why Choose <span class="text-amber">Skyline</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">What makes us Pakistan's most trusted real estate partner</p>
        </div>
        <div class="row g-4 stagger-children">
            @php
                $features = [
                    ['ti ti-badge-check', 'Verified Listings', 'Every property is thoroughly vetted and verified before listing.'],
                    ['ti ti-user-star', 'Expert Agents', 'Professional agents with deep local market knowledge.'],
                    ['ti ti-clock-hour-4', 'Fast Process', 'From search to closing — streamlined and efficient.'],
                    ['ti ti-shield-lock', 'Legal Support', 'Complete documentation and legal guidance included.'],
                    ['ti ti-chart-bar', 'Market Insights', 'Data-driven pricing and trend analysis for smarter decisions.'],
                    ['ti ti-heart-handshake', 'After-Sale Care', 'Relationships don\'t end at the deal — we\'re always here.'],
                ];
            @endphp
            @foreach($features as $feat)
            <div class="col-md-6 col-lg-4 reveal">
                <div class="feature-grid-item">
                    <div class="feat-icon"><i class="{{ $feat[0] }}"></i></div>
                    <h5>{{ $feat[1] }}</h5>
                    <p>{{ $feat[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURED PROPERTIES --}}
<section class="section-darker">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-star"></i></span>
            <h2 class="mt-3">Featured <span class="text-amber">Properties</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Hand-picked premium properties for you</p>
        </div>
        <div class="row g-4 stagger-children">
            @forelse($featuredProperties as $idx => $property)
            <div class="col-md-6 col-lg-4 reveal">
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
            <div class="col-12 empty-state-dark reveal">
                <i class="ti ti-building"></i>
                <p>No properties available yet. Check back soon!</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-5 reveal">
            <a href="{{ route('website.properties') }}" class="btn btn-amber btn-lg px-5">View All Properties <i class="ti ti-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>

{{-- PROPERTY TYPES --}}
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-category"></i></span>
            <h2 class="mt-3">Browse by <span class="text-amber">Type</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Find the property type that fits your lifestyle</p>
        </div>
        @php
            $propertyTypes = [
                ['ti ti-building-house', 'Houses', 'house'],
                ['ti ti-building', 'Flats', 'flat'],
                ['ti ti-map', 'Plots', 'plot'],
                ['ti ti-building-store', 'Commercial', 'commercial'],
                ['ti ti-tree', 'Farmhouses', 'farmhouse'],
                ['ti ti-crown', 'Penthouses', 'penthouse'],
            ];
        @endphp
        <div class="row g-4 stagger-children">
            @foreach($propertyTypes as $pt)
            <div class="col-6 col-md-4 col-lg-2 reveal">
                <a href="{{ route('website.properties', ['type' => $pt[2]]) }}" class="type-showcase-card d-block">
                    <span class="type-icon"><i class="{{ $pt[0] }}"></i></span>
                    <h6>{{ $pt[1] }}</h6>
                    <span class="type-count">Browse All</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="section-darker" style="position:relative;overflow:hidden;">
    <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(212,162,78,0.06) 0%,transparent 70%);border-radius:50%;pointer-events:none;"></div>
    <div class="container position-relative">
        <div class="text-center mb-5 reveal">
            <h2>Our <span class="text-amber">Numbers</span> Speak</h2>
            <div class="accent-line"></div>
        </div>
        <div class="row g-4 text-center stagger-children">
            <div class="col-6 col-md-3 reveal">
                <div class="glass-card text-center py-4">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-building"></i></div>
                    <div class="counter-num" data-count="{{ $stats['properties'] ?? 0 }}">0</div>
                    <div class="stat-label mt-1">Total Properties</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal">
                <div class="glass-card text-center py-4">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-badge-check"></i></div>
                    <div class="counter-num" data-count="{{ $stats['sold'] ?? 0 }}">0</div>
                    <div class="stat-label mt-1">Properties Sold</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal">
                <div class="glass-card text-center py-4">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-user-star"></i></div>
                    <div class="counter-num" data-count="{{ $stats['agents'] ?? 0 }}">0</div>
                    <div class="stat-label mt-1">Expert Agents</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal">
                <div class="glass-card text-center py-4">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-mood-smile"></i></div>
                    <div class="counter-num" data-count="{{ $stats['clients'] ?? 0 }}">0</div>
                    <div class="stat-label mt-1">Happy Clients</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PARALLAX BLOCK --}}
<section class="section-dark" style="padding:2rem 0;">
    <div class="container reveal-scale">
        <div class="parallax-block">
            <div class="parallax-inner" style="background-image:url('{{ asset('assets/img/parallax-bg.jpg') }}');">
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(15,17,21,0.85),rgba(26,29,36,0.7));"></div>
            </div>
            <div class="parallax-content">
                <div>
                    <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);margin-bottom:1rem;">Pakistan's Most Trusted<br><span class="text-amber">Real Estate Platform</span></h2>
                    <p class="text-secondary mb-4" style="max-width:500px;margin:0 auto;">Over a decade of experience helping families find their perfect homes and investors grow their portfolios.</p>
                    <a href="{{ route('website.about') }}" class="btn btn-amber px-4">Learn More About Us <i class="ti ti-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CITIES --}}
@if($cities->count())
<section class="section-darker">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-map-2"></i></span>
            <h2 class="mt-3">Explore by <span class="text-amber">City</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Find properties in your preferred location</p>
        </div>
        <div class="row g-4 stagger-children">
            @php
                $cityIcons = ['Lahore' => 'ti ti-mosque', 'Karachi' => 'ti ti-building', 'Islamabad' => 'ti ti-building-skyscraper', 'Rawalpindi' => 'ti ti-building', 'Faisalabad' => 'ti ti-industry', 'Peshawar' => 'ti ti-mountain', 'Multan' => 'ti ti-sun', 'Quetta' => 'ti ti-terrain'];
            @endphp
            @foreach($cities as $idx => $city)
            <div class="col-6 col-md-4 col-lg-3 reveal">
                <a href="{{ route('website.properties', ['city' => $city]) }}" class="text-decoration-none">
                    <div class="city-card-creative">
                        <div class="city-overlay-grad"></div>
                        <i class="{{ $cityIcons[$city] ?? 'ti ti-building' }} city-bg-icon"></i>
                        <div class="city-arrow"><i class="ti ti-arrow-up-right"></i></div>
                        <div class="city-content">
                            <div class="city-name">{{ $city }}</div>
                            <div class="city-sub">Browse properties <i class="ti ti-arrow-right ms-1"></i></div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TESTIMONIALS --}}
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="icon-box-circle mb-3"><i class="ti ti-message-circle"></i></span>
            <h2 class="mt-3">What Clients <span class="text-amber">Say</span></h2>
            <div class="accent-line"></div>
        </div>

        @php
            $fallbackReviews = collect([
                (object)['name' => 'Ahmed Khan', 'rating' => 5, 'comment' => 'Skyline made our home-buying experience seamless. Their agents were professional, knowledgeable, and always available when we needed them.', 'property' => null],
                (object)['name' => 'Sara Ali', 'rating' => 5, 'comment' => 'Found the perfect commercial space within a week. The team understood exactly what I needed and delivered beyond expectations.', 'property' => null],
                (object)['name' => 'Usman Malik', 'rating' => 5, 'comment' => 'Transparent process, verified listings, and excellent after-sale support. Highly recommended for anyone looking for property in Pakistan.', 'property' => null],
            ]);
            $reviewItems = $reviews->isNotEmpty() ? $reviews : $fallbackReviews;
        @endphp

        <div class="testimonial-carousel reveal">
            <button class="tc-nav tc-prev" type="button" aria-label="Previous"><i class="ti ti-chevron-left"></i></button>
            <div class="tc-track" id="tcTrack">
                @foreach($reviewItems as $rv)
                <div class="tc-slide">
                    <div class="testimonial-card h-100">
                        <div class="tc-stars">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="ti ti-star{{ ($rv->rating ?? 5) >= $s ? '-filled' : '' }}"></i>
                            @endfor
                        </div>
                        <div class="tc-text">"{{ $rv->comment }}"</div>
                        <div class="tc-author">
                            @php $initials = collect(explode(' ', $rv->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join(''); @endphp
                            <div class="tc-avatar">{{ $initials }}</div>
                            <div>
                                <div class="tc-name">{{ $rv->name }}</div>
                                <div class="tc-role">{{ $rv->property && $rv->property->city ? 'Property in ' . $rv->property->city : 'Valued Client' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="tc-nav tc-next" type="button" aria-label="Next"><i class="ti ti-chevron-right"></i></button>
        </div>
        <div class="tc-dots" id="tcDots"></div>
    </div>
</section>

{{-- FAQ --}}
<section class="section-darker">
    <div class="container">
        <div class="row align-items-start">
            <div class="col-lg-4 mb-4 mb-lg-0 reveal-left">
                <span class="icon-box-circle mb-3"><i class="ti ti-help"></i></span>
                <h2 class="mt-3">Frequently Asked <span class="text-amber">Questions</span></h2>
                <div class="accent-line" style="margin-left:0;"></div>
                <p class="text-secondary mb-4">Got questions? We've got answers. If you can't find what you're looking for, feel free to contact us.</p>
                <a href="{{ route('website.contact') }}" class="btn btn-outline-amber"><i class="ti ti-mail me-1"></i> Ask a Question</a>
            </div>
            <div class="col-lg-8 reveal-right">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How do I list my property with Skyline?</span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Simply contact our team through the contact page or call us directly. Our agents will visit your property, conduct a market analysis, and help you list it at the best price with professional photography and marketing.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Are all properties on Skyline verified?</span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Yes, every property undergoes a thorough verification process. Our team physically visits and validates all listings to ensure accuracy in details, pricing, and ownership documentation.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What areas does Skyline operate in?</span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">We operate across all major cities in Pakistan including Lahore, Karachi, Islamabad, Rawalpindi, Faisalabad, Peshawar, Multan, and Quetta with expanding coverage.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Do you offer legal and documentation support?</span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Absolutely. Our team provides end-to-end legal support including contract drafting, title verification, registry assistance, and all necessary documentation for a smooth transaction.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How can I schedule a property visit?</span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">You can schedule a visit directly from any property listing page, or contact us via phone or WhatsApp. Our agents are flexible and can arrange visits at your preferred time.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="section-dark newsletter-section">
    <div class="container reveal">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>Stay <span class="text-amber">Updated</span></h2>
                <div class="accent-line" style="margin-left:0;"></div>
                <p class="text-secondary">Get the latest property listings, market trends, and exclusive offers delivered to your inbox.</p>
            </div>
            <div class="col-lg-6">
                <div class="newsletter-form ms-lg-auto">
                    <form action="#" method="POST" class="input-group">
                        @csrf
                        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        <button type="submit" class="btn btn-amber">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-band" style="position:relative;overflow:hidden;">
    <div class="position-absolute" style="top:0;left:0;right:0;bottom:0;background:radial-gradient(ellipse at center, rgba(212,162,78,0.08) 0%, transparent 70%);pointer-events:none;"></div>
    <svg class="position-absolute bottom-0 start-0 w-100" style="height:50px;opacity:0.15;" viewBox="0 0 1440 50" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <rect x="0" y="20" width="1440" height="30" fill="#D4A24E" opacity="0.1"/>
        <rect x="20" y="5" width="30" height="45" fill="#D4A24E" opacity="0.15"/><rect x="60" y="10" width="25" height="40" fill="#D4A24E" opacity="0.15"/><rect x="100" y="0" width="35" height="50" fill="#D4A24E" opacity="0.15"/>
        <rect x="160" y="8" width="28" height="42" fill="#D4A24E" opacity="0.15"/><rect x="200" y="12" width="22" height="38" fill="#D4A24E" opacity="0.15"/><rect x="240" y="3" width="40" height="47" fill="#D4A24E" opacity="0.15"/>
        <rect x="300" y="7" width="30" height="43" fill="#D4A24E" opacity="0.15"/><rect x="350" y="11" width="25" height="39" fill="#D4A24E" opacity="0.15"/><rect x="400" y="2" width="38" height="48" fill="#D4A24E" opacity="0.15"/>
    </svg>
    <div class="container position-relative" style="z-index:2;">
        <h2 class="mb-3 reveal">Ready to Find Your <span class="text-amber">Next Property?</span></h2>
        <p class="text-secondary mb-4 mx-auto reveal" style="max-width:520px;">Talk to our expert team and discover the perfect property that matches your needs and budget.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactInfo['phone'] ?? '923001234567') }}" class="btn btn-amber btn-lg px-4" target="_blank">
                <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp Us
            </a>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactInfo['phone'] ?? '+923001234567') }}" class="btn btn-outline-amber btn-lg px-4">
                <i class="ti ti-phone me-1"></i> Call Now
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Scroll Reveal (Intersection Observer) ──
    var revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    if ('IntersectionObserver' in window && revealElements.length) {
        var revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach(function(el) { revealObserver.observe(el); });
    } else {
        revealElements.forEach(function(el) { el.classList.add('revealed'); });
    }

    // ── Counter Animation ──
    var counters = document.querySelectorAll('.counter-num[data-count]');
    if (counters.length) {
        var counterAnimated = false;
        var counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !counterAnimated) {
                    counterAnimated = true;
                    counters.forEach(function(el) {
                        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
                        var duration = 2000;
                        var start = 0;
                        var startTime = null;

                        function step(timestamp) {
                            if (!startTime) startTime = timestamp;
                            var progress = Math.min((timestamp - startTime) / duration, 1);
                            var eased = 1 - Math.pow(1 - progress, 3);
                            el.textContent = Math.floor(eased * target).toLocaleString();
                            if (progress < 1) {
                                requestAnimationFrame(step);
                            } else {
                                el.textContent = target.toLocaleString();
                            }
                        }
                        requestAnimationFrame(step);
                    });
                    counterObserver.disconnect();
                }
            });
        }, { threshold: 0.3 });

        if (counters[0]) counterObserver.observe(counters[0]);
    }

    // ── FAQ Accordion ──
    var faqItems = document.querySelectorAll('.faq-item .faq-question');
    faqItems.forEach(function(q) {
        q.addEventListener('click', function() {
            var item = this.closest('.faq-item');
            var isActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item.active').forEach(function(a) { a.classList.remove('active'); });
            if (!isActive) item.classList.add('active');
        });
    });

    // ── How It Works Journey Line Animation ──
    var howSection = document.querySelector('.how-journey');
    if (howSection) {
        var lineFill = document.getElementById('howLineFill');
        var lineDots = howSection.querySelectorAll('.line-dot');
        var howObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    if (lineFill) {
                        lineFill.classList.add('animate');
                        lineDots.forEach(function(dot, i) {
                            setTimeout(function() { dot.classList.add('active'); }, 400 + i * 450);
                        });
                    }
                    howObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.25 });
        howObserver.observe(howSection);
    }

    // ── Testimonial Carousel ──
    var tcTrack = document.getElementById('tcTrack');
    if (tcTrack) {
        var tcPrev = document.querySelector('.tc-prev');
        var tcNext = document.querySelector('.tc-next');
        var tcDotsWrap = document.getElementById('tcDots');
        var tcSlides = tcTrack.querySelectorAll('.tc-slide');

        function tcStep() {
            var slide = tcTrack.querySelector('.tc-slide');
            if (!slide) return tcTrack.clientWidth * 0.8;
            var gap = parseInt(getComputedStyle(tcTrack).columnGap || getComputedStyle(tcTrack).gap) || 20;
            return slide.getBoundingClientRect().width + gap;
        }
        function tcScrollTo(index) {
            var max = tcTrack.scrollWidth - tcTrack.clientWidth;
            var target = Math.max(0, Math.min(index * tcStep(), max));
            tcTrack.scrollTo({ left: target, behavior: 'smooth' });
        }
        if (tcPrev) tcPrev.addEventListener('click', function() { tcTrack.scrollBy({ left: -tcStep(), behavior: 'smooth' }); });
        if (tcNext) tcNext.addEventListener('click', function() { tcTrack.scrollBy({ left: tcStep(), behavior: 'smooth' }); });

        if (tcDotsWrap && tcSlides.length > 1) {
            for (var d = 0; d < tcSlides.length; d++) {
                var dot = document.createElement('button');
                dot.setAttribute('type', 'button');
                dot.setAttribute('aria-label', 'Go to testimonial ' + (d + 1));
                (function(i) { dot.addEventListener('click', function() { tcScrollTo(i); }); })(d);
                tcDotsWrap.appendChild(dot);
            }
            var tcDotsBtns = tcDotsWrap.querySelectorAll('button');
            function tcUpdateDots() {
                var step = tcStep();
                var active = Math.round(tcTrack.scrollLeft / step);
                tcDotsBtns.forEach(function(b, i) { b.classList.toggle('active', i === active); });
            }
            tcTrack.addEventListener('scroll', tcUpdateDots);
            tcUpdateDots();
        }
    }

});
</script>
@endpush
