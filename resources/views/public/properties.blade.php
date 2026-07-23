@extends('public.layouts.app')

@section('title', 'Properties')

@push('styles')
<style>
/* ═══════════════════════ BANNER ═══════════════════════ */
.prop-listing-banner{
  min-height:40vh;display:flex;align-items:center;position:relative;overflow:hidden;
  background:linear-gradient(135deg,var(--primary) 0%,#0f3460 50%,var(--primary-light) 100%);
}
.prop-listing-banner .mesh-bg{position:absolute;inset:0;overflow:hidden}
.prop-listing-banner .mesh-bg .blob{
  position:absolute;border-radius:50%;filter:blur(80px);opacity:.25;
  animation:blobMove 20s ease-in-out infinite alternate;
}
.prop-listing-banner .mesh-bg .blob:nth-child(1){width:400px;height:400px;background:var(--accent);left:-10%;top:-10%;animation-duration:22s}
.prop-listing-banner .mesh-bg .blob:nth-child(2){width:300px;height:300px;background:var(--gold);right:-5%;bottom:-10%;animation-duration:18s;animation-delay:-6s}
@keyframes blobMove{
  0%{transform:translate(0,0) scale(1) rotate(0deg)}
  33%{transform:translate(40px,-30px) scale(1.1) rotate(120deg)}
  66%{transform:translate(-20px,40px) scale(.9) rotate(240deg)}
  100%{transform:translate(30px,-20px) scale(1) rotate(360deg)}
}

/* ═══════════════════════ FILTERS ═══════════════════════ */
.filter-panel{
  background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 8px 40px rgba(0,0,0,.06);
  margin-top:-2.5rem;position:relative;z-index:2;
}
.filter-panel .form-control,.filter-panel .form-select{
  border:2px solid #eef0f4;border-radius:12px;padding:.6rem 1rem;font-size:.88rem;transition:all .3s;
}
.filter-panel .form-control:focus,.filter-panel .form-select:focus{
  border-color:var(--accent);box-shadow:0 0 0 4px rgba(233,69,96,.1);
}
.filter-panel .filter-label{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#888;margin-bottom:.3rem}
.filter-panel .btn-filter{
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;border:none;
  border-radius:12px;padding:.6rem 1.5rem;font-weight:700;transition:all .3s;
}
.filter-panel .btn-filter:hover{transform:scale(1.03);box-shadow:0 6px 20px rgba(233,69,96,.3)}
.filter-panel .btn-reset{border:2px solid #ddd;border-radius:12px;padding:.6rem 1.5rem;font-weight:600;color:#888;background:transparent;transition:all .3s;text-decoration:none;display:inline-flex;align-items:center;}
.filter-panel .btn-reset:hover{background:#f5f5f5;color:#555}
.filter-panel .result-count{
  font-size:.88rem;color:var(--text-muted);display:flex;align-items:center;gap:.5rem;
}

/* ═══════════════════════ PROPERTY CARDS ═══════════════════════ */
.prop-card-3d{perspective:600px;height:100%}
.prop-card-inner{
  background:#fff;border-radius:20px;overflow:hidden;box-shadow:var(--card-shadow);
  transition:all .4s;height:100%;transform-style:preserve-3d;
}
.prop-card-3d:hover .prop-card-inner{transform:rotateY(-4deg) rotateX(3deg);box-shadow:0 24px 80px rgba(0,0,0,.12)}
.prop-card-inner .img-wrap{position:relative;overflow:hidden;height:200px}
.prop-card-inner .img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .6s}
.prop-card-3d:hover .img-wrap img{transform:scale(1.1)}
.prop-card-inner .img-wrap .img-shade{
  position:absolute;inset:0;
  background:linear-gradient(to top,rgba(0,0,0,.3),transparent 50%);
  opacity:0;transition:opacity .4s;
}
.prop-card-3d:hover .img-wrap .img-shade{opacity:1}
.prop-card-inner .badge-type{
  position:absolute;top:12px;left:12px;z-index:2;
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;
  font-size:.65rem;font-weight:700;padding:.22rem .75rem;border-radius:50px;
  text-transform:uppercase;letter-spacing:.04em;box-shadow:0 4px 12px rgba(233,69,96,.3);
}
.prop-card-inner .price-chip{
  position:absolute;bottom:12px;right:12px;z-index:2;
  background:rgba(0,0,0,.6);backdrop-filter:blur(8px);color:#fff;font-weight:800;
  padding:.25rem 1rem;border-radius:50px;font-size:.82rem;border:1px solid rgba(255,255,255,.08);
}
.prop-card-inner .card-body{padding:1.15rem 1.25rem}
.prop-card-inner .prop-city{
  font-size:.68rem;font-weight:600;color:var(--accent);text-transform:uppercase;letter-spacing:.04em;
}
.prop-card-inner .prop-title{font-size:.95rem;font-weight:700;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prop-card-inner .prop-addr{font-size:.78rem;color:#999}
.prop-card-inner .prop-features{display:flex;gap:.85rem;font-size:.78rem;color:#777;border-top:1px solid #f0f0f0;padding-top:.75rem}
.prop-card-inner .prop-features span{display:flex;align-items:center;gap:.25rem}

/* ═══════════════════════ PAGINATION ═══════════════════════ */
.pagy-wrap{display:flex;justify-content:center;gap:.35rem;flex-wrap:wrap}
.pagy-wrap .page-item{list-style:none}
.pagy-wrap .page-link{
  border:none!important;border-radius:12px!important;padding:.5rem 1rem;
  font-weight:600;color:#666;background:transparent;transition:all .2s;margin:0;
}
.pagy-wrap .page-item.active .page-link{background:var(--primary)!important;color:#fff!important;box-shadow:0 4px 12px rgba(26,26,46,.25)}
.pagy-wrap .page-link:hover{background:var(--section-bg);color:var(--primary)}
.pagy-wrap .page-item.disabled .page-link{opacity:.4;pointer-events:none}

/* Empty state */
.empty-state .empty-icon{font-size:5rem;opacity:.1;color:var(--primary)}
.empty-state h5{font-weight:700;color:#555}
</style>
@endpush

@section('content')
{{-- ═══════════════════ BANNER ═══════════════════ --}}
<section class="prop-listing-banner">
  <div class="mesh-bg"><div class="blob"></div><div class="blob"></div></div>
  <div class="container position-relative" style="z-index:1;">
    <div class="row justify-content-center text-center text-white" data-aos="fade-up">
      <div class="col-lg-8">
        <span class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill" style="background:rgba(233,69,96,.15);font-size:.8rem;font-weight:600;backdrop-filter:blur(8px);border:1px solid rgba(233,69,96,.25);">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
          Browse Properties
        </span>
        <h1 style="font-size:clamp(2rem,4vw,3rem);font-weight:800;letter-spacing:-1px;">Find Your Perfect Property</h1>
        <p style="color:rgba(255,255,255,.65);font-size:1.05rem;max-width:500px;margin:.5rem auto 0;">Explore thousands of verified listings across Pakistan</p>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════ FILTERS ═══════════════════ --}}
<section style="background:var(--section-bg);padding-bottom:3rem;">
  <div class="container">
    <div class="filter-panel" data-aos="fade-up">
      <form action="{{ route('website.properties') }}" method="GET">
        <div class="row g-3 align-items-end">
          <div class="col-lg-3">
            <div class="filter-label"><i class="ti ti-search me-1"></i>Search</div>
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="City, area or title…">
          </div>
          <div class="col-lg-2 col-md-4">
            <div class="filter-label"><i class="ti ti-layout-grid me-1"></i>Type</div>
            <select class="form-select" name="type">
              <option value="">All Types</option>
              @foreach($types as $t)
              <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-4">
            <div class="filter-label"><i class="ti ti-map-pin me-1"></i>City</div>
            <select class="form-select" name="city">
              <option value="">All Cities</option>
              @foreach($cities as $c)
              <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-4">
            <div class="filter-label"><i class="ti ti-arrows-transfer me-1"></i>Purpose</div>
            <select class="form-select" name="transaction_type">
              <option value="">All</option>
              <option value="sale" {{ request('transaction_type') == 'sale' ? 'selected' : '' }}>Sale</option>
              <option value="rent" {{ request('transaction_type') == 'rent' ? 'selected' : '' }}>Rent</option>
              <option value="lease" {{ request('transaction_type') == 'lease' ? 'selected' : '' }}>Lease</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-4">
            <div class="filter-label"><i class="ti ti-bed me-1"></i>Bedrooms</div>
            <select class="form-select" name="bedrooms">
              <option value="">Any</option>
              @for($b=1;$b<=10;$b++)
              <option value="{{ $b }}" {{ request('bedrooms') == $b ? 'selected' : '' }}>{{ $b }}+</option>
              @endfor
            </select>
          </div>
          <div class="col-lg-1 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-filter w-100"><i class="ti ti-filter me-1"></i></button>
            <a href="{{ route('website.properties') }}" class="btn btn-reset"><i class="ti ti-x"></i></a>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <div class="result-count">
              <i class="ti ti-building"></i>
              <span><strong>{{ $properties->total() }}</strong> properties found</span>
              @if(request()->anyFilled(['search','type','city','transaction_type','bedrooms']))
              <a href="{{ route('website.properties') }}" class="text-decoration-none small ms-2" style="color:var(--accent);">
                <i class="ti ti-x"></i> Clear filters
              </a>
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>

    {{-- ═══════════════════ LISTINGS ═══════════════════ --}}
    <div class="row g-4">
      @forelse($properties as $property)
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 80 }}">
        <div class="prop-card-3d">
          <a href="{{ route('website.property', $property) }}" class="prop-card-inner text-decoration-none d-flex flex-column" style="color:inherit;">
            <div class="img-wrap">
              @php $primary = $property->primaryMedia; @endphp
              @if($primary)
              <img src="{{ Storage::url($primary->file_path) }}" alt="{{ $property->title }}" loading="lazy">
              @else
              <div class="d-flex align-items-center justify-content-center bg-light text-secondary h-100">
                <i class="ti ti-building" style="font-size:3rem;opacity:.25;"></i>
              </div>
              @endif
              <div class="img-shade"></div>
              <span class="badge-type">{{ ucfirst($property->transaction_type) }}</span>
              <span class="price-chip">{{ number_format($property->price,0) }} {{ $property->currency ?? 'PKR' }}</span>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="prop-city"><i class="ti ti-map-pin me-1"></i>{{ $property->city ?? 'N/A' }}</div>
              <h6 class="prop-title mb-1">{{ $property->title }}</h6>
              <p class="prop-addr mb-2">{{ Str::limit($property->location_address ?? $property->sector_town ?? '', 55) }}</p>
              <div class="prop-features mt-auto">
                @if($property->bedrooms) <span><i class="ti ti-bed"></i>{{ $property->bedrooms }}</span> @endif
                @if($property->bathrooms) <span><i class="ti ti-droplet"></i>{{ $property->bathrooms }}</span> @endif
                @if($property->plot_size) <span><i class="ti ti-ruler"></i>{{ $property->plot_size }} {{ $property->plot_size_unit }}</span> @endif
              </div>
            </div>
          </a>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="empty-state text-center py-5" data-aos="fade-up">
          <div class="empty-icon"><i class="ti ti-building"></i></div>
          <h5>No Properties Found</h5>
          <p class="text-secondary">Try adjusting your filters or search criteria.</p>
          <a href="{{ route('website.properties') }}" class="btn btn-accent btn-lg rounded-pill px-4 mt-2">
            <i class="ti ti-x me-1"></i>Clear All Filters
          </a>
        </div>
      </div>
      @endforelse
    </div>

    {{-- ═══════════════════ PAGINATION ═══════════════════ --}}
    @if($properties->hasPages())
    <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
      {{ $properties->onEachSide(1)->links() }}
    </div>
    @endif
  </div>
</section>
@endsection
