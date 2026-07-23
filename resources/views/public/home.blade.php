@extends('public.layouts.app')

@section('title', 'Home')
@section('meta_description', config('app.name') . ' - Pakistan\'s premier real estate platform. Find houses, flats, plots, and commercial properties for sale and rent across all major cities.')
@section('meta_keywords', 'real estate Pakistan, buy property, sell property, rent house, Islamabad real estate, Lahore property, Karachi property')

@push('styles')
<style>
.hero-carousel { height: 100vh; min-height: 600px; position: relative; overflow: hidden; }
.hero-carousel .carousel-inner, .hero-carousel .carousel-item { height: 100%; }
.hero-carousel .slide-bg { position: absolute; inset: 0; background-size: cover; background-position: center; }
.hero-carousel .slide-bg::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,.35); }
.hero-search-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10; width: 100%; padding: 0 1rem; }
.hero-search-overlay .input-group { background: #fff; border-radius: 60px; padding: 4px; box-shadow: 0 8px 40px rgba(0,0,0,.25); max-width: 800px; margin: 0 auto; }
.hero-search-overlay .form-control, .hero-search-overlay .form-select { border: none; background: transparent; padding: .75rem 1.25rem; }
.hero-search-overlay .form-control:focus, .hero-search-overlay .form-select:focus { box-shadow: none; }
.hero-search-overlay .btn-search { border-radius: 60px!important; padding: .6rem 1.75rem; font-weight: 700; background: var(--accent); color: #fff; border: none; }
.hero-search-overlay .btn-search:hover { background: var(--accent-hover); }
.stats-bar { background: #fff; margin-top: -2.5rem; position: relative; z-index: 10; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 2rem; }
.stat-item { text-align: center; }
.stat-item .stat-num { font-size: 2rem; font-weight: 800; color: var(--primary); line-height: 1; }
.stat-item .stat-label { font-size: .82rem; color: var(--text-muted); font-weight: 500; margin-top: .25rem; }
.stat-item .stat-icon { font-size: 1.5rem; color: var(--accent); margin-bottom: .5rem; display: block; }
.type-card { background: #fff; border-radius: 16px; padding: 1.75rem 1.25rem; text-align: center; box-shadow: var(--card-shadow); transition: all .3s; height: 100%; text-decoration: none; display: block; }
.type-card:hover { transform: translateY(-6px); box-shadow: var(--card-hover-shadow); }
.type-card .type-icon { font-size: 2.2rem; margin-bottom: .75rem; display: block; }
.type-card h6 { font-weight: 700; color: #333; margin-bottom: .25rem; }
.type-card .type-count { font-size: .78rem; color: var(--text-muted); }
.prop-card-vert { border: none; border-radius: 16px; overflow: hidden; box-shadow: var(--card-shadow); transition: all .3s; background: #fff; height: 100%; }
.prop-card-vert:hover { transform: translateY(-6px); box-shadow: var(--card-hover-shadow); }
.prop-card-vert .img-wrap { height: 200px; position: relative; overflow: hidden; }
.prop-card-vert .img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s; }
.prop-card-vert:hover .img-wrap img { transform: scale(1.1); }
.prop-card-vert .badge-tag { position: absolute; top: 12px; left: 12px; background: var(--accent); color: #fff; font-size: .68rem; font-weight: 700; padding: .25rem .75rem; border-radius: 50px; text-transform: uppercase; }
.prop-card-vert .price-chip { position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,.7); backdrop-filter: blur(8px); color: #fff; font-weight: 800; padding: .25rem .85rem; border-radius: 50px; font-size: .82rem; }
.prop-card-vert .card-body { padding: 1.15rem; }
.prop-card-vert .prop-title { font-size: .95rem; font-weight: 700; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.prop-card-vert .prop-location { font-size: .78rem; color: #999; }
.prop-card-vert .prop-meta { display: flex; gap: .75rem; font-size: .78rem; color: #777; border-top: 1px solid #f0f0f0; padding-top: .75rem; }
.testi-card { background: #fff; border-radius: 20px; padding: 2rem; box-shadow: var(--card-shadow); height: 100%; }
.testi-card .stars { color: var(--gold); }
.testi-card .testi-text { font-size: .95rem; line-height: 1.7; color: #555; font-style: italic; }
.testi-card .testi-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.why-card { padding: 1.5rem; border-radius: 16px; background: #fff; box-shadow: var(--card-shadow); transition: all .3s; height: 100%; }
.why-card:hover { transform: translateY(-4px); box-shadow: var(--card-hover-shadow); }
.why-card .why-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #fff; background: linear-gradient(135deg, var(--primary), var(--accent)); margin-bottom: 1rem; }
.why-card h6 { font-weight: 700; }
.why-card p { font-size: .85rem; color: var(--text-muted); margin-bottom: 0; }
.city-card { border-radius: 16px; overflow: hidden; position: relative; height: 240px; display: block; text-decoration: none; }
.city-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s; }
.city-card:hover img { transform: scale(1.1); }
.city-card .overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(26,26,46,.9) 0%, rgba(26,26,46,.1) 60%, transparent 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 1.25rem; transition: all .3s; }
.city-card:hover .overlay { background: linear-gradient(to top, rgba(233,69,96,.85) 0%, rgba(233,69,96,.2) 60%, transparent 100%); }
.city-card .city-name { color: #fff; font-weight: 800; font-size: 1.15rem; }
.city-card .city-count { color: rgba(255,255,255,.7); font-size: .8rem; }
.cta-section { background: linear-gradient(135deg, var(--primary) 0%, #0f3460 50%, var(--primary-light) 100%); border-radius: 20px; padding: 3rem; position: relative; overflow: hidden; }
.cta-section h2 { font-weight: 800; }
.trust-bar { border-top: 1px solid rgba(0,0,0,.04); overflow: hidden; }
.trust-track { display: flex; gap: 3rem; animation: marquee 25s linear infinite; width: max-content; }
.trust-track:hover { animation-play-state: paused; }
@keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.trust-track .trust-item { display: flex; align-items: center; gap: .6rem; color: #bbb; font-weight: 600; white-space: nowrap; }
@property --num { syntax: '<integer>'; initial-value: 0; inherits: false; }
.counter { animation: count 2s ease-out forwards; }
@keyframes count { from { --num: 0; } to { --num: var(--target); } }
</style>
@endpush

@section('content')
{{-- ═══════ HERO CAROUSEL SLIDER ═══════ --}}
<section class="hero-carousel">
  <div id="heroSlider" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner h-100">
      @forelse($sliderImages as $i => $slide)
      <div class="carousel-item h-100 {{ $i === 0 ? 'active' : '' }}">
        <div class="slide-bg" style="background-image: url('{{ Storage::url($slide['image']) }}');"></div>
      </div>
      @empty
      <div class="carousel-item active h-100">
        <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1600&q=80');"></div>
      </div>
      @endforelse
    </div>

    {{-- Search overlay --}}
    <div class="hero-search-overlay">
      <div class="container">
        <form action="{{ route('website.properties') }}" method="GET">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="input-group input-group-lg">
                <span class="input-group-text bg-transparent border-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Search by city, area or property title…">
                <select class="form-select" name="type" style="max-width:140px;">
                  <option value="">All Types</option>
                  @foreach(['house','flat','plot','commercial','farmhouse','penthouse'] as $t)
                  <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                  @endforeach
                </select>
                <select class="form-select" name="transaction_type" style="max-width:120px;">
                  <option value="">All</option>
                  <option value="sale">Sale</option>
                  <option value="rent">Rent</option>
                  <option value="lease">Lease</option>
                </select>
                <button class="btn btn-search" type="submit"><i class="ti ti-search me-1"></i> Search</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

{{-- ═══════ STATS BAR ═══════ --}}
<div class="container">
  <div class="stats-bar">
    <div class="row g-3">
      @php $statItems = [
        ['icon' => 'ti ti-building', 'label' => 'Properties', 'key' => 'properties'],
        ['icon' => 'ti ti-handshake', 'label' => 'Deals Closed', 'key' => 'sold'],
        ['icon' => 'ti ti-briefcase', 'label' => 'Expert Agents', 'key' => 'agents'],
        ['icon' => 'ti ti-users', 'label' => 'Happy Clients', 'key' => 'clients'],
      ]; @endphp
      @foreach($statItems as $s)
      <div class="col-6 col-md-3">
        <div class="stat-item">
          <i class="ti {{ $s['icon'] }} stat-icon"></i>
          <div class="stat-num">{{ number_format($stats[$s['key']]) }}</div>
          <div class="stat-label">{{ $s['label'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══════ PROPERTY TYPES ═══════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-layout-grid"></i> Browse by Category</span>
      <h2 class="section-title">Property Types</h2>
      <p class="section-subtitle">Explore our diverse range of property categories</p>
    </div>
    <div class="row g-3">
      @php $typeConfig = [
        'house' => ['icon' => 'ti ti-home', 'color' => '#e94560'],
        'flat' => ['icon' => 'ti ti-building-community', 'color' => '#0f3460'],
        'plot' => ['icon' => 'ti ti-map', 'color' => '#f5a623'],
        'commercial' => ['icon' => 'ti ti-building-skyscraper', 'color' => '#1a1a2e'],
        'farmhouse' => ['icon' => 'ti ti-tree', 'color' => '#2d6a4f'],
        'penthouse' => ['icon' => 'ti ti-home-star', 'color' => '#7209b7'],
      ]; @endphp
      @foreach($typeConfig as $slug => $cfg)
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('website.properties', ['type' => $slug]) }}" class="type-card">
          <i class="ti {{ $cfg['icon'] }} type-icon" style="color:{{ $cfg['color'] }}"></i>
          <h6>{{ ucfirst($slug) }}</h6>
          <span class="type-count">{{ $typeCounts[$slug] ?? 0 }} listings</span>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════ FEATURED PROPERTIES ═══════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5">
      <div>
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-star"></i> Hand-picked</span>
        <h2 class="section-title mb-0">Featured Properties</h2>
        <p class="text-secondary mt-1 mb-0">Curated selections of the finest properties available</p>
      </div>
    </div>
    <div class="row g-4">
      @forelse($featuredProperties as $property)
      <div class="col-md-6 col-lg-4">
        <div class="prop-card-vert">
          <div class="img-wrap">
            @php $img = $property->primaryMedia ?? $property->media->where('type','image')->first(); @endphp
            @if($img)
            <img src="{{ Storage::url($img->file_path) }}" alt="{{ $property->title }}" loading="lazy">
            @else
            <div class="d-flex align-items-center justify-content-center bg-light text-secondary h-100"><i class="ti ti-building" style="font-size:3rem;opacity:.25;"></i></div>
            @endif
            <span class="badge-tag">{{ ucfirst($property->transaction_type) }}</span>
            <span class="price-chip">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'PKR' }}</span>
          </div>
          <a href="{{ route('website.property', $property) }}" class="card-body text-decoration-none d-block" style="color:inherit;">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="small fw-semibold" style="color:var(--accent);"><i class="ti ti-map-pin me-1"></i>{{ $property->city ?? 'N/A' }}</span>
              @if($property->possession_status) <span class="badge bg-dark" style="font-size:.6rem;">{{ ucfirst(str_replace('_',' ',$property->possession_status)) }}</span> @endif
            </div>
            <h6 class="prop-title mb-1">{{ $property->title }}</h6>
            <p class="prop-location mb-2">{{ Str::limit($property->location_address ?? $property->sector_town ?? '', 50) }}</p>
            <div class="prop-meta">
              @if($property->bedrooms) <span><i class="ti ti-bed"></i> {{ $property->bedrooms }}</span> @endif
              @if($property->bathrooms) <span><i class="ti ti-droplet"></i> {{ $property->bathrooms }}</span> @endif
              @if($property->plot_size) <span><i class="ti ti-ruler"></i> {{ $property->plot_size }} {{ $property->plot_size_unit }}</span> @endif
            </div>
          </a>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="ti ti-building" style="font-size:4rem;opacity:.15;"></i>
        <p class="text-secondary mt-3">No featured properties yet.</p>
        <a href="{{ route('website.properties') }}" class="btn btn-accent mt-2">Browse All</a>
      </div>
      @endforelse
    </div>
    @if($featuredProperties->count())
    <div class="text-center mt-5">
      <a href="{{ route('website.properties') }}" class="btn btn-accent btn-lg px-5 rounded-pill fw-bold">
        View All Properties <i class="ti ti-arrow-right ms-2"></i>
      </a>
    </div>
    @endif
  </div>
</section>

{{-- ═══════ HOW IT WORKS ═══════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-steps"></i> Simple Process</span>
      <h2 class="section-title">How It Works</h2>
      <p class="section-subtitle">Buy, sell, or rent in just 4 easy steps</p>
    </div>
    <div class="row g-4">
      @php $steps = [
        ['icon' => 'ti ti-search', 'title' => 'Search', 'desc' => 'Browse thousands of listings with smart filters.'],
        ['icon' => 'ti ti-calendar-event', 'title' => 'Visit', 'desc' => 'Schedule a site visit at your convenience.'],
        ['icon' => 'ti ti-file-description', 'title' => 'Deal', 'desc' => 'Expert negotiation & documentation support.'],
        ['icon' => 'ti ti-home-check', 'title' => 'Possession', 'desc' => 'Legal assistance for a smooth handover.'],
      ]; @endphp
      @foreach($steps as $i => $s)
      <div class="col-md-3">
        <div class="text-center">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:rgba(233,69,96,.1);">
            <i class="ti {{ $s['icon'] }}" style="font-size:1.4rem;color:var(--accent);"></i>
          </div>
          <span class="d-block fw-bold text-accent small mb-1">Step {{ $i + 1 }}</span>
          <h6 class="fw-bold">{{ $s['title'] }}</h6>
          <p class="small text-secondary mb-0" style="max-width:240px;margin:0 auto;">{{ $s['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════ TESTIMONIALS ═══════ --}}
@if(count($testimonials))
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-message-star"></i> Testimonials</span>
      <h2 class="section-title">What Our Clients Say</h2>
      <p class="section-subtitle">Real stories from real clients who trusted us</p>
    </div>
    <div class="row g-4">
      @foreach($testimonials as $t)
      <div class="col-md-6 col-lg-3">
        <div class="testi-card">
          <div class="stars mb-2">
            @for($r=0;$r<($t['rating']??5);$r++) <i class="ti ti-star-filled"></i>@endfor
          </div>
          <p class="testi-text mb-3">"{{ $t['text'] }}"</p>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:var(--primary);color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;">
              {{ substr($t['name'], 0, 1) }}
            </div>
            <div>
              <div class="fw-bold small">{{ $t['name'] }}</div>
              <div class="small text-secondary">{{ $t['role'] ?? '' }}</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════ CITIES ═══════ --}}
@if($cities->count())
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-map-pins"></i> Explore</span>
      <h2 class="section-title">Cities We Serve</h2>
      <p class="section-subtitle">Find properties across Pakistan's major cities</p>
    </div>
    <div class="row g-4">
      @php $cityImages = [
        'Islamabad' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&q=80',
        'Lahore' => 'https://images.unsplash.com/photo-1599571234909-29ed5d1021d4?w=400&q=80',
        'Karachi' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f9e6?w=400&q=80',
      ]; @endphp
      @foreach($cities as $city)
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('website.properties', ['city' => $city]) }}" class="city-card">
          <img src="{{ $cityImages[$city] ?? 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&q=80' }}" alt="{{ $city }}" loading="lazy">
          <div class="overlay">
            <div class="city-name">{{ $city }}</div>
            <div class="city-count">{{ \App\Models\Property::where('city',$city)->whereIn('status',['available','pending'])->count() }} properties</div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════ WHY CHOOSE US ═══════ --}}
@if(count($features))
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-star"></i> Why Choose Us</span>
      <h2 class="section-title">Why {{ config('app.name') }}?</h2>
      <p class="section-subtitle">We make property deals transparent, fast, and hassle-free</p>
    </div>
    <div class="row g-4">
      @foreach($features as $f)
      <div class="col-md-6 col-lg-4">
        <div class="why-card">
          <div class="why-icon"><i class="ti {{ $f['icon'] ?? 'ti ti-check' }}"></i></div>
          <h6>{{ $f['title'] }}</h6>
          <p>{{ $f['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════ CTA ═══════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="cta-section">
      <div class="row align-items-center g-4 position-relative" style="z-index:1;">
        <div class="col-lg-7 text-white">
          <span class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill" style="background:rgba(255,255,255,.1);font-size:.78rem;font-weight:600;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
            Get Started Today
          </span>
          <h2 class="display-5 fw-bold mb-3">Ready to Find Your Perfect Property?</h2>
          <p style="color:rgba(255,255,255,.65);font-size:1.05rem;max-width:480px;">Get in touch with our team for a free consultation. Let us help you make the right move.</p>
        </div>
        <div class="col-lg-5 text-lg-end">
          <a href="{{ route('website.properties') }}" class="btn btn-accent btn-lg rounded-pill px-4 fw-bold me-2">
            Browse Properties <i class="ti ti-arrow-right ms-1"></i>
          </a>
          <a href="{{ route('website.contact') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold mt-2 mt-lg-0">
            <i class="ti ti-headset me-1"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════ TRUST BAR ═══════ --}}
@if(count($brands ?? []))
<section class="trust-bar py-4">
  <div class="container">
    <p class="text-center text-secondary small text-uppercase mb-3 fw-semibold" style="letter-spacing:.1em;">Trusted by industry leaders</p>
  </div>
  <div class="trust-track">
    @for($x=0;$x<2;$x++) @foreach($brands as $b)
    <span class="trust-item"><i class="ti ti-building-skyscraper"></i> {{ $b }}</span>
    @endforeach @endfor
  </div>
</section>
@endif
@endsection

@push('scripts')
@endpush
