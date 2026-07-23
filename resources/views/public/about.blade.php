@extends('public.layouts.app')

@section('title', 'About Us')
@section('meta_description', 'Learn about ' . config('app.name') . ' — our journey, mission, and the team behind Pakistan\'s most trusted real estate platform.')
@section('meta_keywords', 'about, real estate company, Pakistan property, our team, mission')

@push('styles')
<style>
/* ═══════════════════════ BANNER ═══════════════════════ */
.about-banner{
  min-height:50vh;display:flex;align-items:center;position:relative;overflow:hidden;
  background:linear-gradient(135deg,var(--primary) 0%,#0f3460 50%,var(--primary-light) 100%);
}
.about-banner .mesh-bg{position:absolute;inset:0;overflow:hidden}
.about-banner .mesh-bg .blob{
  position:absolute;border-radius:50%;filter:blur(80px);opacity:.25;
  animation:blobMove 20s ease-in-out infinite alternate;
}
.about-banner .mesh-bg .blob:nth-child(1){width:400px;height:400px;background:var(--accent);left:-5%;top:-10%;animation-duration:18s}
.about-banner .mesh-bg .blob:nth-child(2){width:300px;height:300px;background:var(--gold);right:-5%;bottom:-10%;animation-duration:22s;animation-delay:-6s}
.about-banner .mesh-bg .blob:nth-child(3){width:250px;height:250px;background:#7209b7;left:40%;top:20%;animation-duration:15s;animation-delay:-10s}
@keyframes blobMove{
  0%{transform:translate(0,0) scale(1) rotate(0deg)}
  33%{transform:translate(40px,-30px) scale(1.1) rotate(120deg)}
  66%{transform:translate(-20px,40px) scale(.9) rotate(240deg)}
  100%{transform:translate(30px,-20px) scale(1) rotate(360deg)}
}
.about-banner .banner-shapes{position:absolute;inset:0;pointer-events:none;overflow:hidden}
.about-banner .banner-shapes span{
  position:absolute;width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.15);
  animation:particleDrift linear infinite;
}
@keyframes particleDrift{
  0%{transform:translateY(100vh) scale(0);opacity:0}
  20%{opacity:1}
  80%{opacity:1}
  100%{transform:translateY(-20vh) scale(1);opacity:0}
}

/* ═══════════════════════ STORY ═══════════════════════ */
.story-section{background:#fff}
.story-img-wrap{
  border-radius:24px;overflow:hidden;position:relative;
  box-shadow:0 20px 60px rgba(0,0,0,.1);
}
.story-img-wrap .story-badge{
  position:absolute;bottom:-16px;left:-16px;
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;
  padding:1.25rem 1.5rem;border-radius:16px;box-shadow:0 8px 30px rgba(233,69,96,.35);
}
.story-img-wrap .story-badge .big-num{font-size:2.2rem;font-weight:800;line-height:1}
.story-img-wrap .story-badge .lbl{font-size:.75rem;opacity:.85}
.story-text{font-size:1.05rem;line-height:1.7}

/* ═══════════════════════ TIMELINE ═══════════════════════ */
.timeline{position:relative;padding:2rem 0}
.timeline::before{
  content:'';position:absolute;left:50%;top:0;bottom:0;width:2px;
  background:linear-gradient(to bottom,transparent,var(--accent),var(--accent),transparent);
  transform:translateX(-50%);
}
.timeline-item{display:flex;align-items:flex-start;margin-bottom:3rem;position:relative}
.timeline-item:nth-child(odd){flex-direction:row}
.timeline-item:nth-child(even){flex-direction:row-reverse}
.timeline-item .tl-content{width:calc(50% - 40px);padding:1.5rem;background:#fff;border-radius:16px;box-shadow:var(--card-shadow);transition:all .3s}
.timeline-item .tl-content:hover{transform:translateY(-4px);box-shadow:var(--card-hover-shadow)}
.timeline-item .tl-dot{
  position:absolute;left:50%;transform:translateX(-50%);z-index:2;
  width:32px;height:32px;border-radius:50%;background:var(--accent);color:#fff;
  display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;
  box-shadow:0 0 0 6px rgba(233,69,96,.15);margin-top:.5rem;
}
.timeline-item .tl-year{font-size:.78rem;color:var(--accent);font-weight:700;letter-spacing:.05em}
.timeline-item h6{font-weight:700}
@media(max-width:768px){
  .timeline::before{left:20px}
  .timeline-item,.timeline-item:nth-child(even){flex-direction:row!important}
  .timeline-item .tl-content{width:calc(100% - 60px);margin-left:50px}
  .timeline-item .tl-dot{left:20px}
}

/* ═══════════════════════ MISSION ═══════════════════════ */
.mission-card{
  background:#fff;border-radius:20px;padding:2.5rem 2rem;text-align:center;
  box-shadow:var(--card-shadow);transition:all .4s;position:relative;overflow:hidden;height:100%;
}
.mission-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:4px;
  background:linear-gradient(90deg,var(--primary),var(--accent),var(--gold));
  transform:scaleX(0);transform-origin:left;transition:transform .4s;
}
.mission-card:hover::before{transform:scaleX(1)}
.mission-card:hover{transform:translateY(-8px);box-shadow:0 16px 48px rgba(0,0,0,.1)}
.mission-card .mis-icon{
  width:64px;height:64px;border-radius:16px;margin:0 auto 1.25rem;
  display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;
  background:linear-gradient(135deg,var(--primary),var(--accent));
  transition:all .4s;
}
.mission-card:hover .mis-icon{transform:scale(1.1) rotate(-5deg);border-radius:50%}
.mission-card h5{font-weight:700}
.mission-card p{font-size:.9rem;color:var(--text-muted)}

/* ═══════════════════════ TEAM ═══════════════════════ */
.team-card-3d{perspective:600px;height:100%}
.team-card-inner{
  background:#fff;border-radius:20px;overflow:hidden;box-shadow:var(--card-shadow);
  transition:all .4s;height:100%;transform-style:preserve-3d;
}
.team-card-3d:hover .team-card-inner{transform:rotateY(-6deg) rotateX(4deg);box-shadow:0 20px 60px rgba(0,0,0,.12)}
.team-card-3d .team-photo{height:280px;position:relative;overflow:hidden}
.team-card-3d .team-photo img{width:100%;height:100%;object-fit:cover;transition:transform .6s}
.team-card-3d:hover .team-photo img{transform:scale(1.08)}
.team-card-3d .team-photo .photo-overlay{
  position:absolute;inset:0;
  background:linear-gradient(to top,rgba(26,26,46,.7),transparent 50%);
  opacity:0;transition:opacity .4s;
}
.team-card-3d:hover .team-photo .photo-overlay{opacity:1}
.team-card-3d .team-social{
  position:absolute;bottom:12px;left:50%;transform:translateX(-50%);display:flex;gap:8px;
  opacity:0;transition:all .3s;
}
.team-card-3d:hover .team-social{opacity:1}
.team-card-3d .team-social a{
  width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.9);
  display:flex;align-items:center;justify-content:center;color:var(--primary);
  font-size:.8rem;transition:all .3s;text-decoration:none;
}
.team-card-3d .team-social a:hover{background:var(--accent);color:#fff}
.team-card-3d .card-body{text-align:center;padding:1.25rem}
.team-card-3d .team-name{font-weight:700;font-size:1rem}
.team-card-3d .team-role{font-size:.82rem;color:var(--text-muted)}

/* ═══════════════════════ CTA ═══════════════════════ */
.about-cta{
  background:linear-gradient(135deg,var(--primary) 0%,#0f3460 50%,var(--primary-light) 100%);
  position:relative;overflow:hidden;
}
.about-cta .glow{
  position:absolute;width:300px;height:300px;border-radius:50%;
  background:radial-gradient(circle,var(--accent),transparent 70%);
  opacity:.1;top:-100px;right:-80px;animation:pulse 4s ease-in-out infinite alternate;
}
@keyframes pulse{0%{opacity:.05;transform:scale(1)}100%{opacity:.15;transform:scale(1.2)}}
</style>
@endpush

@section('content')
{{-- ═══════════════════ BANNER ═══════════════════ --}}
<section class="about-banner">
  <div class="mesh-bg"><div class="blob"></div><div class="blob"></div><div class="blob"></div></div>
  <div class="banner-shapes">
    @for($i=0;$i<20;$i++)<span style="left:{{ rand(0,100) }}%;animation-duration:{{ 8+rand(0,15) }}s;animation-delay:{{ rand(0,10) }}s;width:{{ 3+rand(0,6) }}px;height:{{ 3+rand(0,6) }}px"></span>@endfor
  </div>
  <div class="container position-relative" style="z-index:1;">
    <div class="row justify-content-center text-center text-white" data-aos="fade-up">
      <div class="col-lg-8">
        <span class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill" style="background:rgba(233,69,96,.15);font-size:.8rem;font-weight:600;backdrop-filter:blur(8px);border:1px solid rgba(233,69,96,.25);">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
          Our Story
        </span>
        <h1 style="font-size:clamp(2.2rem,5vw,3.5rem);font-weight:800;letter-spacing:-1px;">About {{ config('app.name') }}</h1>
        <p style="color:rgba(255,255,255,.65);font-size:1.1rem;max-width:540px;margin:1rem auto 0;">Learn about our journey, mission, and the team behind Pakistan's most trusted real estate platform.</p>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════ STORY ═══════════════════ --}}
<section class="story-section py-5">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="story-img-wrap">
          <img src="https://images.unsplash.com/photo-1560520653-9e0e4c89eb11?w=600&q=80" alt="Our Story" class="img-fluid" style="display:block;">
          <div class="story-badge">
            <div class="big-num">{{ date('Y') - 2018 }}+</div>
            <div class="lbl">Years of Excellence</div>
          </div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
          <i class="ti ti-book"></i> Our Journey
        </span>
        <h2 class="display-6 fw-bold mb-3" style="color:var(--primary);letter-spacing:-.5px;">Built on Trust, Driven by Excellence</h2>
        <p class="story-text text-secondary">Founded in 2018, {{ config('app.name') }} began with a simple vision — to transform the real estate landscape in Pakistan by making property transactions transparent, accessible, and stress-free for everyone.</p>
        <p class="story-text text-secondary">Over the years, we've grown from a small team of passionate agents to one of the country's most trusted property platforms. Our secret? We put our clients first, leverage cutting-edge technology, and maintain uncompromising integrity in every deal.</p>
        <p class="story-text text-secondary mb-0">With thousands of successful transactions and hundreds of five-star reviews, we're just getting started.</p>
        <div class="d-flex gap-4 mt-4">
          <div><div class="fw-bold" style="font-size:1.8rem;color:var(--accent);">500+</div><div class="small text-secondary">Properties Sold</div></div>
          <div><div class="fw-bold" style="font-size:1.8rem;color:var(--accent);">98%</div><div class="small text-secondary">Satisfaction Rate</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════ TIMELINE ═══════════════════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-timeline"></i> Milestones
      </span>
      <h2 class="section-title">Our Journey So Far</h2>
      <p class="section-subtitle">Key milestones that shaped our growth</p>
    </div>
    <div class="timeline" data-aos="fade-up">
      @php $milestones = [
        ['year'=>'2018','title'=>'Company Founded','desc'=>'Started operations in Islamabad with a team of 5 passionate real estate experts.'],
        ['year'=>'2019','title'=>'100 Properties Sold','desc'=>'Crossed the 100 properties milestone within our first year of operations.'],
        ['year'=>'2020','title'=>'Expanded to 5 Cities','desc'=>'Extended our presence to Lahore, Karachi, and other major cities across Pakistan.'],
        ['year'=>'2022','title'=>'1000+ Happy Clients','desc'=>'Reached a milestone of over 1,000 satisfied clients and 500+ successful deals.'],
        ['year'=>'2024','title'=>'Digital Platform Launch','desc'=>'Launched our state-of-the-art digital platform with smart property matching.'],
      ]; @endphp
      @foreach($milestones as $i => $m)
      <div class="timeline-item" data-aos="{{ $i % 2 === 0 ? 'fade-right' : 'fade-left' }}" data-aos-delay="{{ $i * 100 }}">
        <div class="tl-content">
          <div class="tl-year">{{ $m['year'] }}</div>
          <h6>{{ $m['title'] }}</h6>
          <p class="small text-secondary mb-0">{{ $m['desc'] }}</p>
        </div>
        <div class="tl-dot">{{ $i + 1 }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ MISSION ═══════════════════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-bulb"></i> Our Compass
      </span>
      <h2 class="section-title">Mission &amp; Values</h2>
      <p class="section-subtitle">The principles that guide everything we do</p>
    </div>
    <div class="row g-4">
      @php $missions = [
        ['icon'=>'ti ti-eye','title'=>'Our Vision','desc'=>'To be Pakistan\'s most trusted and innovative real estate platform, making property dreams accessible to everyone.','color'=>'var(--accent)'],
        ['icon'=>'ti ti-target','title'=>'Our Mission','desc'=>'To provide transparent, efficient, and hassle-free property solutions with expert guidance at every step.','color'=>'var(--gold)'],
        ['icon'=>'ti ti-heart','title'=>'Our Values','desc'=>'Integrity, transparency, customer-first, innovation, and excellence in everything we do.','color'=>'#2d6a4f'],
      ]; @endphp
      @foreach($missions as $m)
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 120 }}">
        <div class="mission-card">
          <div class="mis-icon"><i class="ti {{ $m['icon'] }}"></i></div>
          <h5>{{ $m['title'] }}</h5>
          <p>{{ $m['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ TEAM ═══════════════════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-users"></i> Our People
      </span>
      <h2 class="section-title">Meet the Team</h2>
      <p class="section-subtitle">Dedicated professionals committed to your real estate success</p>
    </div>
    <div class="row g-4">
      @foreach ($team as $t)
      <div class="col-6 col-lg-3" data-aos="flip-up" data-aos-delay="{{ $loop->index * 100 }}">
        <div class="team-card-3d">
          <div class="team-card-inner">
            <div class="team-photo">
              @if($t->photo)
              <img src="{{ Storage::url($t->photo) }}" alt="{{ $t->name }}" loading="lazy">
              @else
              <div class="d-flex align-items-center justify-content-center bg-light h-100">
                <i class="ti ti-user-circle" style="font-size:5rem;opacity:.15;color:var(--primary);"></i>
              </div>
              @endif
              <div class="photo-overlay"></div>
              <div class="team-social">
                @if($t->whatsapp)<a href="https://wa.me/{{ $t->whatsapp }}" target="_blank" title="WhatsApp"><i class="ti ti-brand-whatsapp"></i></a>@endif
                @if($t->email)<a href="mailto:{{ $t->email }}" title="Email"><i class="ti ti-mail"></i></a>@endif
                @if($t->facebook)<a href="{{ $t->facebook }}" target="_blank" title="Facebook"><i class="ti ti-brand-facebook"></i></a>@endif
                @if($t->linkedin)<a href="{{ $t->linkedin }}" target="_blank" title="LinkedIn"><i class="ti ti-brand-linkedin"></i></a>@endif
                @if($t->instagram)<a href="{{ $t->instagram }}" target="_blank" title="Instagram"><i class="ti ti-brand-instagram"></i></a>@endif
              </div>
            </div>
            <div class="card-body">
              <div class="team-name">{{ $t->name }}</div>
              <div class="team-role">{{ $t->role ?? 'Agent' }}</div>
              @if($t->experience_years || $t->languages)
              <div class="d-flex justify-content-center gap-3 mt-2 small text-secondary">
                @if($t->experience_years)<span><i class="ti ti-clock"></i> {{ $t->experience_years }}+ yrs</span>@endif
                @if($t->languages)<span><i class="ti ti-language"></i> {{ $t->languages }}</span>@endif
              </div>
              @endif
              @if($t->bio)
              <p class="small text-secondary mt-2 mb-0 px-2" style="line-height:1.4;">{{ Str::limit($t->bio, 100) }}</p>
              @endif
              @if($t->specializations)
                @php $specs = is_array($t->specializations) ? $t->specializations : json_decode($t->specializations, true); @endphp
                <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                  @foreach(array_slice($specs, 0, 3) as $spec)
                  <span class="badge" style="background:var(--accent);font-size:.6rem;font-weight:500;">{{ $spec }}</span>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="about-cta py-5">
  <div class="glow"></div>
  <div class="container position-relative text-center text-white" style="z-index:1;" data-aos="fade-up">
    <h2 class="fw-bold mb-2" style="font-size:clamp(1.5rem,3vw,2.5rem);">Want to Work With Us?</h2>
    <p style="color:rgba(255,255,255,.65);max-width:450px;margin:0 auto 1.5rem;">Join our team of expert real estate professionals and build a rewarding career.</p>
    <a href="{{ route('website.contact') }}" class="btn btn-accent btn-lg px-5 py-3 rounded-pill fw-bold">
      <i class="ti ti-headset me-1"></i>Get in Touch
    </a>
  </div>
</section>
@endsection
