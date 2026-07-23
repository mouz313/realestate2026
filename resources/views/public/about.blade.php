@extends('public.layouts.app')

@section('title', 'About Us')
@section('meta_description', 'Learn about ' . config('app.name') . ' — our journey, mission, and the team behind Pakistan\'s most trusted real estate platform.')
@section('meta_keywords', 'about, real estate company, Pakistan property, our team, mission')

@push('styles')
<style>
.milestone-timeline { position: relative; padding: 2rem 0; }
.milestone-timeline::before { content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, var(--accent), var(--primary)); transform: translateX(-50%); }
.milestone-item { position: relative; margin-bottom: 2rem; }
.milestone-item .dot { width: 16px; height: 16px; border-radius: 50%; background: var(--accent); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--accent); position: absolute; left: 50%; top: 8px; transform: translateX(-50%); z-index: 2; }
.milestone-item .content { width: calc(50% - 2rem); padding: 1.25rem; background: #fff; border-radius: 12px; box-shadow: var(--card-shadow); }
.milestone-item:nth-child(odd) .content { margin-left: auto; }
.milestone-item:nth-child(even) .content { margin-right: auto; }
.milestone-item .year { font-size: .75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: .05em; }
.team-card { border: none; border-radius: 16px; overflow: hidden; box-shadow: var(--card-shadow); transition: all .3s; background: #fff; }
.team-card:hover { transform: translateY(-6px); box-shadow: var(--card-hover-shadow); }
.team-card .team-img { width: 100%; height: 280px; object-fit: cover; }
.team-card .card-body { padding: 1.25rem; }
.team-card .social-link { width: 34px; height: 34px; border-radius: 50%; background: var(--section-bg); display: inline-flex; align-items: center; justify-content: center; color: #888; text-decoration: none; transition: all .2s; }
.team-card .social-link:hover { background: var(--accent); color: #fff; }
</style>
@endpush

@section('content')
<section class="page-banner">
  <div class="container">
    <h1 class="text-white">About <span style="color:var(--accent);">Us</span></h1>
    <p>Learn more about our journey and the team behind {{ config('app.name') }}</p>
  </div>
</section>

{{-- Story --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1560520653-9e0e4c89eb11?w=600&q=80" alt="About {{ config('app.name') }}" class="img-fluid rounded-4 shadow-lg">
      </div>
      <div class="col-lg-6">
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-info-circle"></i> Our Story</span>
        <h2 class="fw-bold mt-2 mb-3">{{ \Carbon\Carbon::now()->year - 2018 }}+ Years of Excellence</h2>
        <p class="text-secondary">{{ config('app.name') }} was founded with a simple mission — to make property transactions transparent, efficient, and trustworthy. Over the years, we've grown from a small team to one of Pakistan's most recognized real estate platforms.</p>
        <p class="text-secondary">Our commitment to quality service, verified listings, and client satisfaction has earned us the trust of thousands of clients across Islamabad, Lahore, Karachi, and beyond.</p>
        <div class="d-flex gap-4 mt-4">
          <div>
            <div class="fw-bold" style="font-size:1.5rem;color:var(--accent);">{{ number_format($totalSold ?: 500) }}+</div>
            <div class="small text-secondary">Properties Sold</div>
          </div>
          <div>
            <div class="fw-bold" style="font-size:1.5rem;color:var(--accent);">98%</div>
            <div class="small text-secondary">Satisfaction Rate</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Milestones --}}
@if(count($milestones))
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-timeline"></i> Our Journey</span>
      <h2 class="section-title">Milestones</h2>
      <p class="section-subtitle">Key moments in our growth story</p>
    </div>
    <div class="milestone-timeline">
      @foreach($milestones as $m)
      <div class="milestone-item">
        <div class="dot"></div>
        <div class="content">
          <div class="year">{{ $m['year'] }}</div>
          <h6 class="fw-bold mb-1">{{ $m['title'] }}</h6>
          <p class="small text-secondary mb-0">{{ $m['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Team --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-users"></i> Our Team</span>
      <h2 class="section-title">Meet the Experts</h2>
      <p class="section-subtitle">Dedicated professionals committed to your success</p>
    </div>
    <div class="row g-4">
      @foreach($team as $agent)
      <div class="col-md-6 col-lg-3">
        <div class="team-card h-100">
          @if($agent->photo)
          <img src="{{ Storage::url($agent->photo) }}" alt="{{ $agent->name }}" class="team-img" loading="lazy">
          @else
          <div class="d-flex align-items-center justify-content-center bg-light text-secondary" style="height:280px;">
            <i class="ti ti-user" style="font-size:4rem;opacity:.2;"></i>
          </div>
          @endif
          <div class="card-body d-flex flex-column">
            <h6 class="fw-bold mb-1">{{ $agent->name }}</h6>
            <span class="small text-accent fw-semibold mb-2">{{ $agent->role ?? 'Agent' }}</span>
            @if($agent->experience_years) <span class="small text-secondary mb-1"><i class="ti ti-clock me-1"></i>{{ $agent->experience_years }} years experience</span> @endif
            @if($agent->languages) <span class="small text-secondary mb-2"><i class="ti ti-language me-1"></i>{{ $agent->languages }}</span> @endif
            @if($agent->bio) <p class="small text-secondary mt-auto mb-2">{{ Str::limit($agent->bio, 100) }}</p> @endif
            @if($agent->specializations)
            <div class="d-flex flex-wrap gap-1 mb-3">
              @foreach(array_slice((array) $agent->specializations, 0, 3) as $spec)
              <span class="badge" style="background:var(--section-bg);color:#666;font-weight:500;">{{ $spec }}</span>
              @endforeach
            </div>
            @endif
            <div class="d-flex gap-2 mt-auto">
              @if($agent->whatsapp) <a href="https://wa.me/{{ $agent->whatsapp }}" class="social-link" target="_blank"><i class="ti ti-brand-whatsapp"></i></a> @endif
              @if($agent->email) <a href="mailto:{{ $agent->email }}" class="social-link"><i class="ti ti-mail"></i></a> @endif
              @if($agent->facebook) <a href="{{ $agent->facebook }}" class="social-link" target="_blank"><i class="ti ti-brand-facebook"></i></a> @endif
              @if($agent->linkedin) <a href="{{ $agent->linkedin }}" class="social-link" target="_blank"><i class="ti ti-brand-linkedin"></i></a> @endif
              @if($agent->instagram) <a href="{{ $agent->instagram }}" class="social-link" target="_blank"><i class="ti ti-brand-instagram"></i></a> @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
