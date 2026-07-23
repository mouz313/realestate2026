@extends('public.layouts.app')

@section('title', $property->title)

@push('styles')
<style>
/* ═══════════════════════ GALLERY ═══════════════════════ */
.gallery-section{position:relative;background:var(--primary)}
.gallery-section .mesh-bg{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.gallery-section .mesh-bg .blob{
  position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;
  animation:blobMove 20s ease-in-out infinite alternate;
}
.gallery-section .mesh-bg .blob:nth-child(1){width:400px;height:400px;background:var(--accent);left:-5%;top:-10%;animation-duration:22s}
.gallery-section .mesh-bg .blob:nth-child(2){width:300px;height:300px;background:var(--gold);right:-5%;bottom:-10%;animation-duration:18s;animation-delay:-6s}
@keyframes blobMove{
  0%{transform:translate(0,0) scale(1) rotate(0deg)}
  33%{transform:translate(40px,-30px) scale(1.1) rotate(120deg)}
  66%{transform:translate(-20px,40px) scale(.9) rotate(240deg)}
  100%{transform:translate(30px,-20px) scale(1) rotate(360deg)}
}

.main-img-wrap{
  border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);position:relative;
  background:rgba(0,0,0,.2);cursor:pointer;
}
.main-img-wrap img{width:100%;height:450px;object-fit:cover;display:block;transition:transform .6s}
.main-img-wrap:hover img{transform:scale(1.03)}
.main-img-wrap .img-expand{
  position:absolute;top:16px;right:16px;z-index:2;
  width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,.5);backdrop-filter:blur(8px);
  display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;
  transition:all .3s;cursor:pointer;border:none;
}
.main-img-wrap .img-expand:hover{background:var(--accent)}

/* image counter badge */
.img-counter{
  position:absolute;bottom:16px;left:16px;z-index:2;
  background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
  color:#fff;padding:6px 14px;border-radius:50px;
  font-size:.82rem;font-weight:600;letter-spacing:.03em;
}

/* dots */
.gallery-dots{display:flex;justify-content:center;gap:8px;margin-top:14px}
.gallery-dots .dot{
  width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);
  cursor:pointer;transition:all .3s;border:none;padding:0;
}
.gallery-dots .dot.active{background:var(--accent);width:28px;border-radius:6px}

/* thumbnails with arrows */
.thumb-wrapper{position:relative;display:flex;align-items:center;gap:8px;margin-top:14px}
.thumb-scroll{overflow-x:auto;display:flex;gap:10px;padding:4px 0;scrollbar-width:none;flex:1;scroll-behavior:smooth}
.thumb-scroll::-webkit-scrollbar{display:none}
.thumb-item{
  flex-shrink:0;width:calc(16.666% - 9px);height:80px;border-radius:12px;overflow:hidden;
  cursor:pointer;opacity:.6;transition:all .3s;border:2px solid transparent;position:relative;
}
.thumb-item:hover{opacity:.9}
.thumb-item.active{opacity:1;border-color:var(--accent);box-shadow:0 0 0 3px rgba(233,69,96,.3)}
.thumb-item img{width:100%;height:100%;object-fit:cover}
.thumb-arrow{
  flex-shrink:0;width:36px;height:36px;border-radius:50%;
  background:rgba(255,255,255,.15);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.2);color:#fff;
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;transition:all .25s;font-size:1rem;
}
.thumb-arrow:hover{background:var(--accent);border-color:var(--accent)}

/* ═══════════════════════ BREADCRUMB ═══════════════════════ */
.breadcrumb-custom{
  display:flex;align-items:center;gap:8px;flex-wrap:wrap;padding:.75rem 1.25rem;
  background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border-radius:60px;
  border:1px solid rgba(255,255,255,.1);
}
.breadcrumb-custom a{color:rgba(255,255,255,.6);text-decoration:none;font-size:.82rem;transition:color .2s}
.breadcrumb-custom a:hover{color:#fff}
.breadcrumb-custom .sep{color:rgba(255,255,255,.25);font-size:.7rem}
.breadcrumb-custom .current{color:#fff;font-size:.82rem;font-weight:600}

/* ═══════════════════════ CONTENT ═══════════════════════ */
.detail-section{background:#fff}
.detail-title{font-weight:800;letter-spacing:-.5px;color:var(--primary)}
.detail-price{
  font-size:1.8rem;font-weight:800;
  background:linear-gradient(135deg,var(--accent),var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.detail-meta{display:flex;gap:2rem;flex-wrap:wrap}
.detail-meta .meta-item{text-align:center;padding:.5rem 1.5rem;background:var(--section-bg);border-radius:14px;min-width:80px}
.detail-meta .meta-item .meta-val{font-size:1.2rem;font-weight:800;color:var(--primary)}
.detail-meta .meta-item .meta-lbl{font-size:.72rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.03em}
.detail-desc{font-size:1rem;line-height:1.7;color:#555}

.feature-group{margin-bottom:1.5rem}
.feature-group .fg-title{font-size:.85rem;font-weight:700;color:var(--primary);margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem}
.feature-group .fg-title i{color:var(--accent)}
.feature-badge{
  display:inline-flex;align-items:center;gap:5px;
  background:var(--section-bg);color:#444;padding:.35rem .85rem;border-radius:50px;
  font-size:.78rem;font-weight:500;margin:0 .35rem .35rem 0;transition:all .2s;
}
.feature-badge:hover{background:var(--accent);color:#fff}

/* ═══════════════════════ SIDEBAR ═══════════════════════ */
.sidebar-card{
  background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 8px 40px rgba(0,0,0,.06);
  position:sticky;top:90px;border:1px solid rgba(0,0,0,.04);
}
.sidebar-card .sb-price{font-size:1.8rem;font-weight:800;color:var(--accent)}
.sidebar-card .sb-sub{font-size:.82rem;color:var(--text-muted)}
.sidebar-card .sb-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f0f0f0;font-size:.9rem}
.sidebar-card .sb-row:last-child{border-bottom:none}
.sidebar-card .sb-row .sb-lbl{color:#888}
.sidebar-card .sb-row .sb-val{font-weight:600;color:var(--primary)}
.sidebar-card .sb-agent{
  display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--section-bg);border-radius:14px;
}
.sidebar-card .sb-agent .sa-avatar{width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);padding:2px}
.sidebar-card .sb-agent .sa-name{font-weight:700;font-size:.9rem}
.sidebar-card .sb-agent .sa-role{font-size:.78rem;color:var(--text-muted)}
.sidebar-card .btn-wa{
  background:#25D366;color:#fff;border:none;border-radius:14px;padding:.8rem;font-weight:700;
  transition:all .3s;display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.sidebar-card .btn-wa:hover{background:#20bd5a;color:#fff;transform:translateY(-2px);box-shadow:0 6px 20px rgba(37,211,102,.3)}
.sidebar-card .btn-inq{
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;border:none;
  border-radius:14px;padding:.8rem;font-weight:700;transition:all .3s;
  display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.sidebar-card .btn-inq:hover{color:#fff;transform:translateY(-2px);box-shadow:0 6px 20px rgba(233,69,96,.3)}

/* ═══════════════════════ RELATED ═══════════════════════ */
.related-carousel{overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:1.25rem;padding:.5rem 0 1rem;scrollbar-width:none}
.related-carousel::-webkit-scrollbar{display:none}
.related-carousel .rel-item{min-width:calc(25% - 1rem);scroll-snap-align:start;flex-shrink:0}
@media(max-width:992px){.related-carousel .rel-item{min-width:calc(33.333% - .85rem)}}
@media(max-width:576px){.related-carousel .rel-item{min-width:80%}}

.rel-card{
  border:none;border-radius:16px;overflow:hidden;box-shadow:var(--card-shadow);
  transition:all .3s;background:#fff;display:block;text-decoration:none;color:inherit;
}
.rel-card:hover{transform:translateY(-4px);box-shadow:var(--card-hover-shadow);color:inherit}
.rel-card .rel-img{height:160px;overflow:hidden}
.rel-card .rel-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.rel-card:hover .rel-img img{transform:scale(1.08)}
.rel-card .rel-body{padding:.85rem 1rem}
.rel-card .rel-city{font-size:.65rem;font-weight:600;color:var(--accent);text-transform:uppercase;letter-spacing:.04em}
.rel-card .rel-title{font-size:.85rem;font-weight:700;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.rel-card .rel-price{font-size:.9rem;font-weight:700;color:var(--accent);margin-top:.25rem}

/* ═══════════════════════ LIGHTBOX ═══════════════════════ */
.lightbox{position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.9);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:all .4s}
.lightbox.show{opacity:1;pointer-events:all}
.lightbox img{max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 20px 80px rgba(0,0,0,.5)}
.lightbox .lb-close{position:absolute;top:24px;right:24px;width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.1);border:none;color:#fff;font-size:1.4rem;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center}
.lightbox .lb-close:hover{background:var(--accent)}
</style>
@endpush

@section('content')
@php
$images = $property->media->where('type','image')->values();
$totalImages = $images->count();
$imageUrls = $images->map(fn($m) => Storage::url($m->file_path))->values();
@endphp
{{-- ═══════════════════ GALLERY ═══════════════════ --}}
<section class="gallery-section py-4">
  <div class="mesh-bg"><div class="blob"></div><div class="blob"></div></div>
  <div class="container position-relative" style="z-index:1;">

    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom mb-3" data-aos="fade-up">
      <a href="{{ route('home') }}"><i class="ti ti-home me-1"></i>Home</a>
      <span class="sep"><i class="ti ti-chevron-right"></i></span>
      <a href="{{ route('website.properties') }}">Properties</a>
      <span class="sep"><i class="ti ti-chevron-right"></i></span>
      <span class="current">{{ Str::limit($property->title, 45) }}</span>
    </div>

    {{-- Main Image --}}
    <div class="main-img-wrap" data-aos="fade-up" onclick="openLightbox()">
      @if($totalImages)
      <img id="mainImage" src="{{ Storage::url($images[0]->file_path) }}" alt="{{ $property->title }}">
      <span class="img-counter" id="imgCounter">1 / {{ $totalImages }}</span>
      @else
      <div class="d-flex align-items-center justify-content-center bg-light text-secondary" style="height:450px;">
        <i class="ti ti-building" style="font-size:5rem;opacity:.2;"></i>
      </div>
      @endif
      <button class="img-expand" id="expandBtn" onclick="event.stopPropagation();openLightbox()"><i class="ti ti-arrows-maximize"></i></button>
    </div>

    {{-- Dots --}}
    @if($totalImages > 1)
    <div class="gallery-dots" data-aos="fade-up">
      @foreach($images as $i => $img)
      <button class="dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" onclick="goToImage({{ $i }})"></button>
      @endforeach
    </div>
    @endif

    {{-- Thumbnails with arrows --}}
    @if($totalImages > 1)
    <div class="thumb-wrapper" data-aos="fade-up" data-aos-delay="100">
      <button class="thumb-arrow" onclick="scrollThumbs(-1)" title="Previous"><i class="ti ti-chevron-left"></i></button>
      <div class="thumb-scroll" id="thumbScroll">
        @foreach($images as $i => $img)
        <div class="thumb-item {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" onclick="goToImage({{ $i }})">
          <img src="{{ Storage::url($img->file_path) }}" alt="" loading="lazy">
        </div>
        @endforeach
      </div>
      <button class="thumb-arrow" onclick="scrollThumbs(1)" title="Next"><i class="ti ti-chevron-right"></i></button>
    </div>
    @endif
  </div>
</section>

{{-- ═══════════════════ DETAILS ═══════════════════ --}}
<section class="detail-section py-4">
  <div class="container">
    <div class="row g-5">
      {{-- Left: Content --}}
      <div class="col-lg-8">
        <div data-aos="fade-up">
          <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill fw-semibold small" style="background:rgba(233,69,96,.1);color:var(--accent);">
            <i class="ti ti-tag"></i> {{ ucfirst($property->transaction_type) }}
            @if($property->type) — {{ ucfirst($property->type) }} @endif
          </span>
          <h2 class="detail-title mt-2 mb-2">{{ $property->title }}</h2>
          <p class="text-secondary mb-3">
            <i class="ti ti-map-pin me-1" style="color:var(--accent);"></i>
            {{ $property->city ?? '' }}{{ $property->sector_town ? ', '.$property->sector_town : '' }}{{ $property->location_address ? ' — '.$property->location_address : '' }}
          </p>
        </div>

        {{-- Meta --}}
        <div class="detail-meta mb-4" data-aos="fade-up" data-aos-delay="50">
          @if($property->bedrooms)<div class="meta-item"><div class="meta-val">{{ $property->bedrooms }}</div><div class="meta-lbl"><i class="ti ti-bed"></i> Beds</div></div>@endif
          @if($property->bathrooms)<div class="meta-item"><div class="meta-val">{{ $property->bathrooms }}</div><div class="meta-lbl"><i class="ti ti-droplet"></i> Baths</div></div>@endif
          @if($property->plot_size)<div class="meta-item"><div class="meta-val">{{ $property->plot_size }}</div><div class="meta-lbl"><i class="ti ti-ruler"></i> {{ $property->plot_size_unit ?? 'sqft' }}</div></div>@endif
          @if($property->covered_area)<div class="meta-item"><div class="meta-val">{{ $property->covered_area }}</div><div class="meta-lbl"><i class="ti ti-square"></i> Covered</div></div>@endif
        </div>

        {{-- Price (mobile) --}}
        <div class="d-lg-none mb-4" data-aos="fade-up">
          <div class="detail-price">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'PKR' }}</div>
          @if($property->price_per_sqft)<small class="text-secondary">{{ number_format($property->price_per_sqft, 0) }}/sqft</small>@endif
        </div>

        {{-- Description --}}
        <div class="mb-4" data-aos="fade-up">
          <h5 class="fw-bold mb-2"><i class="ti ti-notes me-1" style="color:var(--accent);"></i> Description</h5>
          <p class="detail-desc">{{ $property->description ?? 'No description provided.' }}</p>
        </div>

        {{-- Features --}}
        <div data-aos="fade-up">
          <h5 class="fw-bold mb-3"><i class="ti ti-list-check me-1" style="color:var(--accent);"></i> Features &amp; Amenities</h5>
          <div class="row">
            @if($property->additional_rooms)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-door"></i> Additional Rooms</div>
              @foreach($property->additional_rooms as $r) <span class="feature-badge"><i class="ti ti-door"></i>{{ $r }}</span> @endforeach</div>
            @endif
            @if($property->building_features)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-building-skyscraper"></i> Building</div>
              @foreach($property->building_features as $f) <span class="feature-badge"><i class="ti ti-building-skyscraper"></i>{{ $f }}</span> @endforeach</div>
            @endif
            @if($property->community_amenities)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-users"></i> Community Amenities</div>
              @foreach($property->community_amenities as $a) <span class="feature-badge"><i class="ti ti-users"></i>{{ $a }}</span> @endforeach</div>
            @endif
            @if($property->communication_features)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-antenna"></i> Communication</div>
              @foreach($property->communication_features as $c) <span class="feature-badge"><i class="ti ti-antenna"></i>{{ $c }}</span> @endforeach</div>
            @endif
            @if($property->nearby_places)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-map-pin"></i> Nearby Places</div>
              @foreach($property->nearby_places as $p) <span class="feature-badge"><i class="ti ti-map-pin"></i>{{ $p }}</span> @endforeach</div>
            @endif
            @if($property->utilities)
            <div class="col-md-6 feature-group"><div class="fg-title"><i class="ti ti-bolt"></i> Utilities</div>
              @foreach($property->utilities as $u) <span class="feature-badge"><i class="ti ti-bolt"></i>{{ $u }}</span> @endforeach</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Right: Sidebar --}}
      <div class="col-lg-4" data-aos="fade-left">
        <div class="sidebar-card">
          <div class="sb-price">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'PKR' }}</div>
          @if($property->price_per_sqft)<div class="sb-sub">{{ number_format($property->price_per_sqft, 0) }}/sqft</div>@endif

          <hr class="my-3">

          <div class="sb-row"><span class="sb-lbl">Type</span><span class="sb-val">{{ ucfirst($property->type ?? 'N/A') }}</span></div>
          <div class="sb-row"><span class="sb-lbl">Purpose</span><span class="sb-val">{{ ucfirst($property->transaction_type) }}</span></div>
          @if($property->bedrooms)<div class="sb-row"><span class="sb-lbl">Bedrooms</span><span class="sb-val">{{ $property->bedrooms }}</span></div>@endif
          @if($property->bathrooms)<div class="sb-row"><span class="sb-lbl">Bathrooms</span><span class="sb-val">{{ $property->bathrooms }}</span></div>@endif
          @if($property->plot_size)<div class="sb-row"><span class="sb-lbl">Plot Size</span><span class="sb-val">{{ $property->plot_size }} {{ $property->plot_size_unit }}</span></div>@endif
          @if($property->covered_area)<div class="sb-row"><span class="sb-lbl">Covered Area</span><span class="sb-val">{{ $property->covered_area }} {{ $property->covered_area_unit }}</span></div>@endif
          @if($property->floor_number !== null)<div class="sb-row"><span class="sb-lbl">Floor</span><span class="sb-val">{{ $property->floor_number }}/{{ $property->total_floors ?? 'N/A' }}</span></div>@endif
          @if($property->furnished)<div class="sb-row"><span class="sb-lbl">Furnished</span><span class="sb-val">{{ ucfirst($property->furnished) }}</span></div>@endif
          <div class="sb-row"><span class="sb-lbl">Possession</span><span class="sb-val">{{ ucfirst(str_replace('_',' ',$property->possession_status ?? 'Immediate')) }}</span></div>

          <hr class="my-3">

          {{-- Agent --}}
          @if($property->assignedAgent)
          @php $agent = $property->assignedAgent; @endphp
          <div class="sb-agent mb-2">
            @if($agent->photo)
            <img src="{{ Storage::url($agent->photo) }}" class="sa-avatar">
            @else
            <div class="sa-avatar d-flex align-items-center justify-content-center" style="background:var(--section-bg);color:var(--text-muted);font-weight:700;">
              {{ strtoupper(substr($agent->name,0,1)) }}
            </div>
            @endif
            <div>
              <div class="sa-name">{{ $agent->name }}</div>
              <div class="sa-role">{{ $agent->role ?? 'Listing Agent' }}</div>
              @if($agent->experience_years)<div class="small text-secondary"><i class="ti ti-clock"></i> {{ $agent->experience_years }}+ yrs exp.</div>@endif
              @if($agent->languages)<div class="small text-secondary"><i class="ti ti-language"></i> {{ $agent->languages }}</div>@endif
            </div>
          </div>
          @php $whatsapp = $agent->whatsapp ? preg_replace('/[^0-9]/', '', $agent->whatsapp) : '923001234567'; @endphp
          <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi, I am interested in: '.$property->title) }}" target="_blank" class="btn-wa d-flex align-items-center justify-content-center gap-2 mb-2">
            <i class="ti ti-brand-whatsapp" style="font-size:1.2rem;"></i> WhatsApp
          </a>
          @endif

          <div class="d-grid gap-2">
            <a href="{{ route('website.contact') }}" class="btn-inq">
              <i class="ti ti-mail"></i> Inquire Now
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════ RELATED ═══════════════════ --}}
    @if($related->count())
    <div class="mt-5 pt-4 border-top" data-aos="fade-up">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;">
            <i class="ti ti-building"></i> You May Also Like
          </span>
          <h4 class="fw-bold mb-0 mt-1">Similar Properties</h4>
        </div>
      </div>
      <div class="related-carousel">
        @foreach($related as $r)
        <div class="rel-item">
          <a href="{{ route('website.property', $r) }}" class="rel-card">
            <div class="rel-img">
              @php $rImg = $r->primaryMedia; @endphp
              @if($rImg)
              <img src="{{ Storage::url($rImg->file_path) }}" alt="{{ $r->title }}" loading="lazy">
              @else
              <div class="d-flex align-items-center justify-content-center bg-light text-secondary h-100">
                <i class="ti ti-building" style="font-size:2rem;opacity:.25;"></i>
              </div>
              @endif
            </div>
            <div class="rel-body">
              <div class="rel-city"><i class="ti ti-map-pin me-1"></i>{{ $r->city ?? 'N/A' }}</div>
              <div class="rel-title">{{ Str::limit($r->title, 45) }}</div>
              <div class="rel-price">{{ number_format($r->price, 0) }} {{ $r->currency ?? 'PKR' }}</div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>

{{-- ═══════════════════ LIGHTBOX ═══════════════════ --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
  <button class="lb-close" onclick="closeLightbox()"><i class="ti ti-x"></i></button>
  <img id="lightboxImg" src="" alt="">
</div>
@endsection

@push('scripts')
<script>
(function(){
  const images = @json($imageUrls);
  const total = images.length;
  let current = 0;
  let autoTimer = null;

  const mainImg = document.getElementById('mainImage');
  const counter = document.getElementById('imgCounter');
  const dots = document.querySelectorAll('.dot');
  const thumbs = document.querySelectorAll('.thumb-item');
  const thumbScroll = document.getElementById('thumbScroll');

  function updateUI(idx){
    if(idx < 0) idx = 0;
    if(idx >= total) idx = total - 1;
    current = idx;
    if(mainImg) mainImg.src = images[idx];
    if(counter) counter.textContent = (idx + 1) + ' / ' + total;
    dots.forEach((d,i) => d.classList.toggle('active', i === idx));
    thumbs.forEach((t,i) => t.classList.toggle('active', i === idx));
  }

  window.goToImage = function(idx){ updateUI(idx); resetAuto(); };

  window.openLightbox = function(){
    if(!total) return;
    const lb = document.getElementById('lightbox');
    document.getElementById('lightboxImg').src = images[current];
    lb.classList.add('show');
    document.body.style.overflow = 'hidden';
    stopAuto();
  };

  window.closeLightbox = function(e){
    if(e && e.target !== e.currentTarget) return;
    document.getElementById('lightbox').classList.remove('show');
    document.body.style.overflow = '';
    startAuto();
  };

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') closeLightbox();
    if(document.getElementById('lightbox').classList.contains('show')){
      if(e.key === 'ArrowLeft'){ e.preventDefault(); navigateLightbox(-1); }
      if(e.key === 'ArrowRight'){ e.preventDefault(); navigateLightbox(1); }
    }
  });

  function navigateLightbox(dir){
    current = (current + dir + total) % total;
    document.getElementById('lightboxImg').src = images[current];
    updateUI(current);
  }

  function nextAuto(){
    current = (current + 1) % total;
    updateUI(current);
  }

  function startAuto(){
    stopAuto();
    if(total > 1) autoTimer = setInterval(nextAuto, 4000);
  }

  function stopAuto(){ if(autoTimer){ clearInterval(autoTimer); autoTimer = null; } }
  function resetAuto(){ stopAuto(); startAuto(); }

  window.scrollThumbs = function(dir){
    if(!thumbScroll) return;
    const scrollAmt = 200;
    thumbScroll.scrollBy({ left: dir * scrollAmt, behavior: 'smooth' });
  };

  // lightbox prev/next buttons
  const lb = document.getElementById('lightbox');
  const lbNav = document.createElement('div');
  lbNav.style.cssText = 'position:absolute;left:0;right:0;top:0;bottom:0;display:flex;justify-content:space-between;align-items:center;pointer-events:none;padding:0 20px;';
  lbNav.innerHTML = '<button style="pointer-events:all;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:1.3rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background=\'var(--accent)\'" onmouseout="this.style.background=\'\'" onclick="event.stopPropagation();navigateLightbox(-1)"><i class="ti ti-chevron-left"></i></button>' +
    '<button style="pointer-events:all;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:1.3rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background=\'var(--accent)\'" onmouseout="this.style.background=\'\'" onclick="event.stopPropagation();navigateLightbox(1)"><i class="ti ti-chevron-right"></i></button>';
  lb.appendChild(lbNav);

  // start auto-slide
  startAuto();
})();
</script>
@endpush
