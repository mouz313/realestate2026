@extends('layouts.website')

@section('title', 'About Us — Skyline Real Estate')
@section('meta_description', 'Learn about our journey, mission, and the team behind Pakistan\'s most trusted real estate platform.')

@section('content')
{{-- HERO --}}
<section class="page-hero-creative" data-hero-label="ABOUT">
    <div class="container position-relative">
        <div class="mb-3">
            <span class="icon-box-circle glow-pulse"><i class="ti ti-building-skyscraper"></i></span>
        </div>
        <h1 class="anim-fade-up anim-delay-1">About <span class="text-amber">Us</span></h1>
        <p class="lead anim-fade-up anim-delay-2">Learn more about our journey, our mission, and the people behind {{ config('app.name') }}</p>
    </div>
</section>

{{-- STORY SECTION --}}
<section class="section-dark">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 anim-fade-up">
                <div class="position-relative">
                    <div class="rounded-4 overflow-hidden" style="background:linear-gradient(135deg, rgba(212,162,78,0.08), rgba(212,162,78,0.02)); height:420px; display:flex; align-items:center; justify-content:center;">
                        <svg viewBox="0 0 300 200" style="width:80%;opacity:0.15;" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20" y="60" width="50" height="140" fill="#D4A24E"/>
                            <rect x="26" y="70" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="40" y="70" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="26" y="90" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="40" y="90" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="26" y="110" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="40" y="110" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="90" y="20" width="60" height="180" fill="#D4A24E"/>
                            <rect x="98" y="30" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="114" y="30" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="130" y="30" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="98" y="50" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="114" y="50" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="130" y="50" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="98" y="70" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="114" y="70" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="130" y="70" width="8" height="8" fill="#0F1115" opacity="0.6"/>
                            <rect x="170" y="80" width="45" height="120" fill="#D4A24E"/>
                            <rect x="176" y="90" width="7" height="7" fill="#0F1115" opacity="0.6"/>
                            <rect x="190" y="90" width="7" height="7" fill="#0F1115" opacity="0.6"/>
                            <rect x="204" y="90" width="7" height="7" fill="#0F1115" opacity="0.6"/>
                            <rect x="176" y="108" width="7" height="7" fill="#0F1115" opacity="0.6"/>
                            <rect x="190" y="108" width="7" height="7" fill="#0F1115" opacity="0.6"/>
                            <rect x="235" y="50" width="40" height="150" fill="#D4A24E"/>
                            <rect x="241" y="60" width="6" height="6" fill="#0F1115" opacity="0.6"/>
                            <rect x="253" y="60" width="6" height="6" fill="#0F1115" opacity="0.6"/>
                            <rect x="241" y="76" width="6" height="6" fill="#0F1115" opacity="0.6"/>
                            <rect x="253" y="76" width="6" height="6" fill="#0F1115" opacity="0.6"/>
                        </svg>
                    </div>
                    <div class="position-absolute" style="bottom:-20px;right:-20px;background:var(--sky-amber);color:var(--sky-bg);padding:1rem 1.5rem;border-radius:12px;font-family:'Playfair Display',serif;font-weight:700;font-size:1.1rem;box-shadow:0 8px 30px rgba(212,162,78,0.3);">
                        {{ \Carbon\Carbon::now()->year - 2018 }}+ Years
                    </div>
                </div>
            </div>
            <div class="col-lg-6 anim-fade-up anim-delay-2">
                <span class="text-amber fw-semibold small text-uppercase" style="letter-spacing:.12em;">Our Story</span>
                <div class="accent-line" style="margin-left:0;"></div>
                <h2 class="fw-bold mb-3" style="font-size:2rem;">Building Trust,<br>One Property at a Time</h2>
                <p class="text-secondary mb-3" style="line-height:1.8;">{{ config('app.name') }} was founded with a simple mission — to make property transactions transparent, efficient, and trustworthy. Over the years, we've grown from a small team to one of Pakistan's most recognized real estate platforms.</p>
                <p class="text-secondary mb-4" style="line-height:1.8;">Our commitment to quality service, verified listings, and client satisfaction has earned us the trust of thousands of clients across Islamabad, Lahore, Karachi, and beyond.</p>
                <div class="row g-3">
                    <div class="col-auto">
                        <div class="glass-card text-center px-4 py-3">
                            <div class="text-amber counter-animate" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;">{{ number_format($totalSold ?: 500) }}+</div>
                            <div class="small text-secondary mt-1">Properties Sold</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="glass-card text-center px-4 py-3">
                            <div class="text-amber counter-animate" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;">98%</div>
                            <div class="small text-secondary mt-1">Satisfaction Rate</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="glass-card text-center px-4 py-3">
                            <div class="text-amber counter-animate" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;">{{ number_format($stats['agents'] ?? 6) }}+</div>
                            <div class="small text-secondary mt-1">Expert Agents</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MILESTONES --}}
@if(count($milestones))
<section class="section-darker">
    <div class="container">
        <div class="text-center mb-5">
            <span class="icon-box-circle mb-3"><i class="ti ti-timeline"></i></span>
            <h2 class="mt-3">Our <span class="text-amber">Milestones</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Key moments that shaped our journey</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="timeline-modern">
                    @foreach($milestones as $idx => $m)
                    <div class="timeline-item anim-fade-up anim-delay-{{ $idx + 1 }}">
                        <div class="glass-card-static">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="text-amber fw-bold small" style="letter-spacing:.08em;">{{ $m['year'] }}</span>
                                <div style="flex:1;height:1px;background:rgba(212,162,78,0.15);"></div>
                            </div>
                            <h5 class="mb-1">{{ $m['title'] }}</h5>
                            <p class="text-secondary small mb-0">{{ $m['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- TEAM --}}
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5">
            <span class="icon-box-circle mb-3"><i class="ti ti-users"></i></span>
            <h2 class="mt-3">Meet the <span class="text-amber">Experts</span></h2>
            <div class="accent-line"></div>
            <p class="text-secondary">Dedicated professionals committed to your success</p>
        </div>
        <div class="row g-4">
            @foreach($team as $idx => $agent)
            <div class="col-md-6 col-lg-3 anim-fade-up anim-delay-{{ $idx + 1 }}">
                <div class="team-card-modern h-100">
                    <div class="team-img-wrap">
                        @if($agent->photo)
                            <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}" loading="lazy">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="ti ti-user" style="font-size:4rem;opacity:.1;color:var(--sky-amber);"></i>
                            </div>
                        @endif
                        <div class="team-overlay"></div>
                        <div class="team-socials">
                            @if($agent->whatsapp) <a href="https://wa.me/{{ ltrim($agent->whatsapp, '+') }}" target="_blank"><i class="ti ti-brand-whatsapp"></i></a> @endif
                            @if($agent->email) <a href="mailto:{{ $agent->email }}"><i class="ti ti-mail"></i></a> @endif
                            @if($agent->facebook) <a href="{{ $agent->facebook }}" target="_blank"><i class="ti ti-brand-facebook"></i></a> @endif
                            @if($agent->linkedin) <a href="{{ $agent->linkedin }}" target="_blank"><i class="ti ti-brand-linkedin"></i></a> @endif
                            @if($agent->instagram) <a href="{{ $agent->instagram }}" target="_blank"><i class="ti ti-brand-instagram"></i></a> @endif
                        </div>
                    </div>
                    <div class="team-body">
                        <h6>{{ $agent->name }}</h6>
                        <div class="team-role mb-2">{{ $agent->role ?? 'Agent' }}</div>
                        @if($agent->experience_years)
                            <small class="text-secondary"><i class="ti ti-clock me-1 text-amber"></i>{{ $agent->experience_years }} years experience</small>
                        @endif
                        @if($agent->bio)
                            <p class="small text-secondary mt-2 mb-0">{{ Str::limit($agent->bio, 80) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- VALUES CTA --}}
<section class="section-darker">
    <div class="container text-center">
        <h2 class="mb-3">Why Choose <span class="text-amber">Skyline</span>?</h2>
        <div class="accent-line"></div>
        <div class="row g-4 mt-4 justify-content-center">
            <div class="col-md-4">
                <div class="glass-card text-center h-100">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-shield-check"></i></div>
                    <h5>Verified Listings</h5>
                    <p class="text-secondary small mb-0">Every property is personally verified by our team before listing.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card text-center h-100">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-headset"></i></div>
                    <h5>24/7 Support</h5>
                    <p class="text-secondary small mb-0">Our agents are available around the clock to assist you.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card text-center h-100">
                    <div class="icon-box-circle mx-auto mb-3"><i class="ti ti-chart-line"></i></div>
                    <h5>Market Insights</h5>
                    <p class="text-secondary small mb-0">Data-driven advice to help you make the right investment.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
