@extends('public.layouts.app')

@section('title', 'Home')
@section('meta_description', config('app.name') . ' - Pakistan\'s premier real estate platform. Find houses, flats, plots, and commercial properties for sale and rent across all major cities.')
@section('meta_keywords', 'real estate Pakistan, buy property, sell property, rent house, Islamabad real estate, Lahore property, Karachi property')

@push('styles')
<style>
/* ═══════════════════════ BASE ═══════════════════════ */
*{scroll-behavior:smooth}
::selection{background:var(--accent);color:#fff}

/* ═══════════════════════ CURSOR FOLLOWER ═══════════════════════ */
.cursor-dot{position:fixed;width:8px;height:8px;border-radius:50%;background:var(--accent);pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:width .3s,height .3s,opacity .3s;opacity:0}
.cursor-ring{position:fixed;width:40px;height:40px;border-radius:50%;border:1.5px solid rgba(233,69,96,.4);pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:width .3s,height .3s,border-color .3s,opacity .3s;opacity:0}

/* ═══════════════════════ HERO ═══════════════════════ */
.hero-section{
  min-height:100vh;display:flex;align-items:center;position:relative;overflow:hidden;
  background:var(--primary);
}
/* Morphing gradient mesh */
.hero-mesh{
  position:absolute;inset:0;overflow:hidden;
}
.hero-mesh .blob{
  position:absolute;border-radius:50%;filter:blur(80px);opacity:.35;
  animation:blobMove 25s ease-in-out infinite alternate;
}
.hero-mesh .blob:nth-child(1){width:500px;height:500px;background:var(--accent);top:-10%;left:-5%;animation-duration:22s}
.hero-mesh .blob:nth-child(2){width:400px;height:400px;background:var(--gold);top:40%;right:-8%;animation-duration:28s;animation-delay:-8s}
.hero-mesh .blob:nth-child(3){width:350px;height:350px;background:#0f3460;bottom:-5%;left:30%;animation-duration:20s;animation-delay:-4s}
.hero-mesh .blob:nth-child(4){width:250px;height:250px;background:#7209b7;top:20%;left:50%;animation-duration:18s;animation-delay:-12s}
@keyframes blobMove{
  0%{transform:translate(0,0) scale(1) rotate(0deg)}
  25%{transform:translate(60px,-40px) scale(1.15) rotate(90deg)}
  50%{transform:translate(-30px,60px) scale(.9) rotate(180deg)}
  75%{transform:translate(40px,30px) scale(1.05) rotate(270deg)}
  100%{transform:translate(-20px,-50px) scale(1) rotate(360deg)}
}
/* 3D buildings */
.hero-buildings{
  position:absolute;bottom:0;left:0;right:0;height:40vh;perspective:800px;
  pointer-events:none;
}
.hero-buildings .bldg{
  position:absolute;bottom:0;width:60px;background:linear-gradient(to top,rgba(255,255,255,.06),rgba(255,255,255,.02));
  border-radius:4px 4px 0 0;transform:rotateX(4deg);transform-origin:bottom center;
  animation:bldgGlow 4s ease-in-out infinite alternate;
}
.hero-buildings .bldg:nth-child(1){left:8%;height:180px;animation-delay:0s}
.hero-buildings .bldg:nth-child(2){left:16%;height:240px;width:50px;animation-delay:-1s}
.hero-buildings .bldg:nth-child(3){left:24%;height:160px;animation-delay:-2s}
.hero-buildings .bldg:nth-child(4){left:32%;height:280px;width:55px;animation-delay:-.5s}
.hero-buildings .bldg:nth-child(5){left:40%;height:200px;animation-delay:-3s}
.hero-buildings .bldg:nth-child(6){left:48%;height:300px;width:70px;animation-delay:-1.5s}
.hero-buildings .bldg:nth-child(7){left:56%;height:170px;animation-delay:-2.5s}
.hero-buildings .bldg:nth-child(8){left:64%;height:230px;width:45px;animation-delay:-.8s}
.hero-buildings .bldg:nth-child(9){left:72%;height:190px;animation-delay:-3.5s}
.hero-buildings .bldg:nth-child(10){left:80%;height:260px;animation-delay:-1.2s}
.hero-buildings .bldg:nth-child(11){left:88%;height:150px;animation-delay:-2.2s}
@keyframes bldgGlow{
  0%{opacity:.4}
  100%{opacity:.8}
}
/* Particles */
.hero-particles{position:absolute;inset:0;pointer-events:none}
.particle{
  position:absolute;width:3px;height:3px;border-radius:50%;background:#fff;
  animation:particleFloat linear infinite;opacity:0;
}
@keyframes particleFloat{
  0%{transform:translateY(0) scale(0);opacity:0}
  10%{opacity:.6}
  90%{opacity:.6}
  100%{transform:translateY(-100vh) scale(1);opacity:0}
}
/* Hero title */
.hero-title{font-size:clamp(2.2rem,5vw,4rem);font-weight:800;line-height:1.1;letter-spacing:-1px}
.hero-title .gradient-text{
  background:linear-gradient(135deg,#fff 30%,var(--accent) 70%,var(--gold) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
/* Word scramble */
.word-scramble{display:inline-block;min-width:2ch}
/* Search bar */
.hero-search{max-width:600px}
.hero-search .input-group{background:rgba(255,255,255,.1);border-radius:60px;padding:5px;backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15)}
.hero-search input,.hero-search select{
  border:none;background:transparent;color:#fff;padding:.65rem 1.25rem;
}
.hero-search input::placeholder{color:rgba(255,255,255,.5)}
.hero-search select option{color:#333}
.hero-search .btn-search{
  border-radius:60px!important;padding:.65rem 2rem;font-weight:700;
  background:linear-gradient(135deg,var(--accent),#d63851);border:none;color:#fff;
  transition:all .3s;position:relative;overflow:hidden;
}
.hero-search .btn-search:hover{transform:scale(1.04);box-shadow:0 8px 30px rgba(233,69,96,.4)}
.hero-search .btn-search::after{
  content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;
  background:linear-gradient(45deg,transparent,transparent,transparent,rgba(255,255,255,.1),transparent,transparent,transparent);
  transform:rotate(45deg);animation:btnShine 3s infinite;
}
@keyframes btnShine{0%{transform:translateX(-100%) rotate(45deg)}100%{transform:translateX(100%) rotate(45deg)}}
/* Hero image float */
.hero-image-wrap{perspective:1000px}
.hero-image-wrap .img-card{
  border-radius:20px;overflow:hidden;box-shadow:0 30px 120px rgba(0,0,0,.4);
  transform:rotateY(-8deg) rotateX(4deg);transition:transform .6s;position:relative;
}
.hero-image-wrap:hover .img-card{transform:rotateY(0deg) rotateX(0deg)}
.hero-floating-badge{
  position:absolute;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);
  border-radius:16px;padding:1rem 1.25rem;box-shadow:0 12px 40px rgba(0,0,0,.2);
  animation:badgeFloat 6s ease-in-out infinite;
}
@keyframes badgeFloat{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-12px)}
}

/* ═══════════════════════ STATS RINGS ═══════════════════════ */
.stats-rings{background:#fff;margin-top:-3rem;z-index:2;position:relative;border-radius:24px;box-shadow:0 12px 60px rgba(0,0,0,.08);padding:3rem 1.5rem}
.ring-card{text-align:center;padding:1rem}
.ring-card .ring-wrap{position:relative;width:100px;height:100px;margin:0 auto .75rem}
.ring-card .ring-wrap svg{transform:rotate(-90deg);width:100px;height:100px}
.ring-card .ring-wrap .ring-bg{fill:none;stroke:var(--section-bg);stroke-width:6}
.ring-card .ring-wrap .ring-fg{fill:none;stroke:url(#ringGrad);stroke-width:6;stroke-linecap:round;
  stroke-dasharray:283;stroke-dashoffset:283;transition:stroke-dashoffset 1.5s cubic-bezier(.4,0,.2,1)}
.ring-card .ring-wrap .ring-num{
  position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;font-weight:800;color:var(--primary);
}
.ring-card .ring-label{font-size:.82rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.05em}
.ring-card .ring-icon{font-size:1.3rem;color:var(--accent);margin-bottom:.25rem}

/* ═══════════════════════ PROPERTY TYPES 3D ═══════════════════════ */
.type-cube{
  perspective:600px;height:100%;
}
.type-cube-inner{
  background:#fff;border-radius:20px;padding:2rem 1.5rem;text-align:center;
  box-shadow:var(--card-shadow);transition:all .4s;height:100%;position:relative;overflow:hidden;
  transform-style:preserve-3d;
}
.type-cube-inner::before{
  content:'';position:absolute;top:0;left:0;right:0;height:4px;
  background:linear-gradient(90deg,transparent,var(--type-color),transparent);
  opacity:0;transition:opacity .4s;
}
.type-cube:hover .type-cube-inner::before{opacity:1}
.type-cube:hover .type-cube-inner{transform:translateY(-8px) rotateX(4deg);box-shadow:0 20px 60px rgba(0,0,0,.12)}
.type-cube .cube-icon{
  font-size:2.8rem;transition:all .4s;display:inline-block;
  background:linear-gradient(135deg,var(--type-color),var(--type-color)60%,transparent);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.type-cube:hover .cube-icon{transform:scale(1.2) rotateY(180deg)}
.type-cube .cube-count{font-size:.75rem;color:var(--text-muted);background:var(--section-bg);padding:.2rem .8rem;border-radius:50px;display:inline-block;margin-top:.5rem}

/* ═══════════════════════ FEATURED CAROUSEL ═══════════════════════ */
.properties-carousel{overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;padding:1rem .5rem 1.5rem;display:flex;gap:1.5rem;scrollbar-width:none}
.properties-carousel::-webkit-scrollbar{display:none}
.properties-carousel .carousel-item-prop{
  min-width:calc(16.666% - 1.2rem);scroll-snap-align:start;flex-shrink:0;
}
@media(max-width:1400px){.properties-carousel .carousel-item-prop{min-width:calc(20% - 1.1rem)}}
@media(max-width:992px){.properties-carousel .carousel-item-prop{min-width:calc(33.333% - 1rem)}}
@media(max-width:576px){.properties-carousel .carousel-item-prop{min-width:calc(50% - .75rem)}}
@media(max-width:400px){.properties-carousel .carousel-item-prop{min-width:85%}}

.prop-card{
  border:none;border-radius:20px;overflow:hidden;box-shadow:var(--card-shadow);
  transition:all .5s cubic-bezier(.4,0,.2,1);background:#fff;
}
.prop-card:hover{transform:translateY(-8px);box-shadow:0 24px 80px rgba(0,0,0,.12)}
.prop-card .img-wrap{position:relative;overflow:hidden;height:220px}
.prop-card .img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .8s}
.prop-card:hover .img-wrap img{transform:scale(1.12)}
.prop-card .img-shimmer{
  position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
  transform:translateX(-100%);transition:transform .6s;
}
.prop-card:hover .img-shimmer{transform:translateX(100%)}
.prop-card .badge-tag{
  position:absolute;top:14px;left:14px;z-index:2;
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;
  font-size:.68rem;font-weight:700;padding:.25rem .85rem;border-radius:50px;text-transform:uppercase;letter-spacing:.04em;
  box-shadow:0 4px 16px rgba(233,69,96,.35);
}
.prop-card .price-floating{
  position:absolute;bottom:14px;right:14px;z-index:2;
  background:rgba(0,0,0,.7);backdrop-filter:blur(8px);color:#fff;font-weight:800;
  padding:.35rem 1rem;border-radius:50px;font-size:.85rem;border:1px solid rgba(255,255,255,.1);
}
.prop-card .card-body{padding:1.25rem}
.prop-card .prop-title{font-size:1rem;font-weight:700;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prop-card .prop-location{font-size:.78rem;color:#888}
.prop-card .prop-meta{display:flex;gap:.75rem;font-size:.78rem;color:#666}
.prop-card .prop-meta span{display:flex;align-items:center;gap:.25rem}
.prop-card .view-btn{
  position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(.8) translateY(10px);
  opacity:0;transition:all .35s;z-index:3;
}
.prop-card:hover .view-btn{opacity:1;transform:translate(-50%,-50%) scale(1) translateY(0)}
/* Carousel arrows */
.carousel-arrows{display:flex;justify-content:center;gap:.75rem;margin-top:.5rem}
.carousel-arrows button{
  width:44px;height:44px;border-radius:50%;border:2px solid #ddd;background:#fff;
  display:flex;align-items:center;justify-content:center;transition:all .3s;cursor:pointer;color:#666;
}
.carousel-arrows button:hover{border-color:var(--accent);background:var(--accent);color:#fff}

/* ═══════════════════════ HOW IT WORKS 3D ═══════════════════════ */
.how-3d{background:var(--section-bg);position:relative}
.how-3d-steps{position:relative;z-index:1}
.how-3d-step{text-align:center;padding:2rem 1rem;position:relative}
.how-3d-step .step-ring{
  width:64px;height:64px;margin:0 auto 1.25rem;position:relative;
  display:flex;align-items:center;justify-content:center;
}
.how-3d-step .step-ring svg{position:absolute;width:64px;height:64px;transform:rotate(-90deg)}
.how-3d-step .step-ring .ring-progress{fill:none;stroke:var(--accent);stroke-width:3;stroke-linecap:round;
  stroke-dasharray:170;stroke-dashoffset:170;transition:stroke-dashoffset 1s cubic-bezier(.4,0,.2,1)}
.how-3d-step .step-num{font-weight:800;font-size:1.1rem;color:var(--accent);position:relative;z-index:1}
.how-3d-step h6{font-weight:700}
.how-3d-step p{font-size:.84rem;color:var(--text-muted);max-width:240px;margin:0 auto}

/* ═══════════════════════ TESTIMONIALS CAROUSEL ═══════════════════════ */
.testi-carousel{position:relative;overflow:hidden;min-height:320px}
.testi-track{display:flex;transition:transform .7s cubic-bezier(.4,0,.2,1)}
.testi-slide{min-width:100%;padding:0 1rem}
.testi-inner{
  background:#fff;border-radius:24px;padding:2.5rem;box-shadow:var(--card-shadow);
  max-width:700px;margin:0 auto;position:relative;
}
.testi-inner .quote-bg{
  position:absolute;top:-20px;left:-10px;font-size:8rem;color:var(--accent);opacity:.04;line-height:1;font-family:Georgia,serif;
}
.testi-stars{color:var(--gold);font-size:.9rem}
.testi-text{font-size:1.05rem;line-height:1.7;color:#555;font-style:italic}
.testi-avatar{width:52px;height:52px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);padding:2px}
.testi-dots{display:flex;justify-content:center;gap:.5rem;margin-top:1.5rem}
.testi-dot{width:10px;height:10px;border-radius:50%;border:none;background:#ddd;cursor:pointer;transition:all .3s;padding:0}
.testi-dot.active{width:28px;border-radius:5px;background:var(--accent)}

/* ═══════════════════════ CITIES PARALLAX ═══════════════════════ */
.city-card{
  border-radius:20px;overflow:hidden;position:relative;height:260px;display:block;text-decoration:none;
  transform:translateZ(0);
}
.city-card img{width:100%;height:100%;object-fit:cover;transition:transform .8s}
.city-card:hover img{transform:scale(1.15)}
.city-card .city-shade{
  position:absolute;inset:0;
  background:linear-gradient(to top,rgba(26,26,46,.9) 0%,rgba(26,26,46,.1) 60%,transparent 100%);
  transition:all .4s;display:flex;flex-direction:column;justify-content:flex-end;padding:1.5rem;
}
.city-card:hover .city-shade{background:linear-gradient(to top,rgba(233,69,96,.85) 0%,rgba(233,69,96,.2) 60%,transparent 100%)}
.city-card .city-name{color:#fff;font-weight:800;font-size:1.25rem}
.city-card .city-count{color:rgba(255,255,255,.75);font-size:.82rem}
.city-card .city-explore{
  margin-top:.5rem;opacity:0;transform:translateY(10px);transition:all .35s;
  color:#fff;font-size:.78rem;font-weight:600;
}
.city-card:hover .city-explore{opacity:1;transform:translateY(0)}

/* ═══════════════════════ WHY US ICONIC ═══════════════════════ */
.why-card{padding:2rem;border-radius:20px;background:#fff;box-shadow:var(--card-shadow);transition:all .4s;position:relative;overflow:hidden}
.why-card::after{
  content:'';position:absolute;top:0;right:0;width:80px;height:80px;
  background:radial-gradient(circle at top right,var(--accent),transparent 70%);
  opacity:0;transition:opacity .4s;
}
.why-card:hover::after{opacity:.08}
.why-card:hover{transform:translateY(-6px);box-shadow:0 16px 48px rgba(0,0,0,.1)}
.why-card .why-icon{
  width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;color:#fff;margin-bottom:1rem;
  background:linear-gradient(135deg,var(--primary),var(--accent));
  transition:all .4s;
}
.why-card:hover .why-icon{transform:scale(1.1) rotate(-5deg);border-radius:50%}
.why-card h6{font-weight:700}
.why-card p{font-size:.85rem;color:var(--text-muted);margin-bottom:0}

/* ═══════════════════════ CTA SPLIT ═══════════════════════ */
.cta-wrap{background:var(--section-bg)}
.cta-inner{
  background:linear-gradient(135deg,var(--primary) 0%,#0f3460 50%,var(--primary-light) 100%);
  border-radius:24px;overflow:hidden;padding:3.5rem 3rem;position:relative;
}
.cta-inner .cta-glow{
  position:absolute;width:300px;height:300px;border-radius:50%;
  background:radial-gradient(circle,var(--accent),transparent 70%);
  opacity:.1;top:-100px;right:-80px;animation:ctaPulse 4s ease-in-out infinite alternate;
}
@keyframes ctaPulse{0%{opacity:.05;transform:scale(1)}100%{opacity:.15;transform:scale(1.2)}}
.cta-inner h2{font-weight:800;letter-spacing:-1px}
.cta-btn{
  padding:.85rem 2.5rem;border-radius:60px;font-weight:700;transition:all .3s;
  position:relative;overflow:hidden;display:inline-flex;align-items:center;gap:.5rem;
}
.cta-btn-primary{
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;border:none;
}
.cta-btn-primary:hover{transform:scale(1.04);color:#fff;box-shadow:0 8px 30px rgba(233,69,96,.4)}
.cta-btn-outline{background:transparent;color:#fff;border:2px solid rgba(255,255,255,.3)}
.cta-btn-outline:hover{background:rgba(255,255,255,.1);border-color:#fff;color:#fff}
.cta-btn .btn-shimmer{
  position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
  transform:translateX(-100%);transition:transform .6s;
}
.cta-btn:hover .btn-shimmer{transform:translateX(100%)}

/* ═══════════════════════ TRUST MARQUEE ═══════════════════════ */
.trust-marquee{overflow:hidden;border-top:1px solid rgba(0,0,0,.04);background:#fff}
.trust-track{display:flex;gap:4rem;animation:marquee 30s linear infinite;width:max-content}
@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.trust-track:hover{animation-play-state:paused}
.trust-track .trust-item{
  display:flex;align-items:center;gap:.75rem;color:#bbb;font-weight:600;font-size:1.05rem;
  white-space:nowrap;transition:color .3s;letter-spacing:.03em;
}
.trust-track .trust-item:hover{color:var(--accent)}
.trust-track .trust-item i{font-size:1.4rem}

/* ═══════════════════════ SCROLL INDICATOR ═══════════════════════ */
.scroll-indicator{
  position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);z-index:2;
  display:flex;flex-direction:column;align-items:center;gap:.5rem;color:rgba(255,255,255,.4);
  font-size:.7rem;text-transform:uppercase;letter-spacing:.1em;animation:fadeInUp 1s 2s forwards;opacity:0;
}
.scroll-mouse{width:24px;height:36px;border:2px solid rgba(255,255,255,.4);border-radius:12px;position:relative}
.scroll-mouse::after{content:'';position:absolute;top:6px;left:50%;transform:translateX(-50%);width:3px;height:8px;border-radius:2px;background:var(--accent);animation:scrollWheel 1.5s ease-in-out infinite}
@keyframes scrollWheel{0%,100%{transform:translateX(-50%) translateY(0);opacity:1}50%{transform:translateX(-50%) translateY(8px);opacity:.3}}
@keyframes fadeInUp{to{opacity:1;transform:translateX(-50%) translateY(0)}}

/* ═══════════════════════ RESPONSIVE ═══════════════════════ */
@media(max-width:768px){
  .hero-section{min-height:90vh}
  .hero-title{font-size:clamp(1.6rem,7vw,2.4rem)}
  .stats-rings{margin-top:-1.5rem;padding:1.5rem .5rem}
  .ring-card .ring-wrap{width:70px;height:70px}
  .ring-card .ring-wrap svg{width:70px;height:70px}
  .cta-inner{padding:2rem 1.5rem}
  .cursor-dot,.cursor-ring{display:none}
}
</style>
@endpush

@section('content')
{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="hero-section" id="hero">
  <div class="hero-mesh"><div class="blob"></div><div class="blob"></div><div class="blob"></div><div class="blob"></div></div>

  <div class="hero-buildings">
    <div class="bldg"></div><div class="bldg"></div><div class="bldg"></div><div class="bldg"></div>
    <div class="bldg"></div><div class="bldg"></div><div class="bldg"></div><div class="bldg"></div>
    <div class="bldg"></div><div class="bldg"></div><div class="bldg"></div>
  </div>

  <div class="hero-particles" id="particles"></div>

  <div class="container position-relative" style="z-index:1;">
    <div class="row align-items-center g-5">
      <div class="col-lg-7 text-white">
        <div data-aos="fade-up" data-aos-duration="800">
          <span class="d-inline-flex align-items-center gap-2 mb-4 px-3 py-1 rounded-pill" style="background:rgba(233,69,96,.15);font-size:.78rem;font-weight:600;letter-spacing:.03em;backdrop-filter:blur(8px);border:1px solid rgba(233,69,96,.25);">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
            Pakistan's #1 Real Estate Platform
          </span>
          <h1 class="hero-title mb-3">
            Find Your<br>
            <span class="gradient-text"><span class="word-scramble" id="scramble"></span></span>
          </h1>
          <p class="mb-4" style="font-size:1.05rem;color:rgba(255,255,255,.65);max-width:520px;line-height:1.6;">
            From luxury villas to modern apartments — discover thousands of verified properties across Pakistan with expert guidance at every step.
          </p>
        </div>

        <form action="{{ route('website.properties') }}" method="GET" class="hero-search" data-aos="fade-up" data-aos-delay="200">
          <div class="input-group input-group-lg">
            <input type="text" class="form-control" name="search" placeholder="Search by city, area or title…">
            <select class="form-select" name="type" style="max-width:130px;">
              <option value="">All Types</option>
              <option value="house">House</option>
              <option value="flat">Flat</option>
              <option value="plot">Plot</option>
              <option value="commercial">Commercial</option>
              <option value="farmhouse">Farmhouse</option>
            </select>
            <button class="btn btn-search" type="submit"><i class="ti ti-search me-1"></i>Search</button>
          </div>
        </form>

        <div class="d-flex flex-wrap gap-4 mt-4 small" data-aos="fade-up" data-aos-delay="300" style="color:rgba(255,255,255,.45);">
          <span class="d-flex align-items-center gap-1"><i class="ti ti-building" style="color:var(--accent)"></i> 1,000+ Properties</span>
          <span class="d-flex align-items-center gap-1"><i class="ti ti-map-pin" style="color:var(--accent)"></i> 15+ Cities</span>
          <span class="d-flex align-items-center gap-1"><i class="ti ti-user-check" style="color:var(--accent)"></i> 100+ Expert Agents</span>
          <span class="d-flex align-items-center gap-1"><i class="ti ti-handshake" style="color:var(--accent)"></i> 500+ Happy Clients</span>
        </div>
      </div>

      <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
        <div class="hero-image-wrap">
          <div class="img-card">
            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80" alt="Dream Home" class="img-fluid" style="min-height:420px;object-fit:cover;display:block;">
          </div>
          <div class="hero-floating-badge top-0 start-0 translate-middle-y" style="animation-delay:0s;">
            <div class="d-flex align-items-center gap-2">
              <i class="ti ti-home" style="font-size:1.5rem;color:var(--accent)"></i>
              <div>
                <div class="fw-bold small" style="color:var(--primary)">250+</div>
                <div style="font-size:.68rem;color:#888">Properties Sold</div>
              </div>
            </div>
          </div>
          <div class="hero-floating-badge bottom-0 end-0 translate-middle-y" style="animation-delay:-3s;">
            <div class="d-flex align-items-center gap-2">
              <i class="ti ti-users" style="font-size:1.5rem;color:var(--accent)"></i>
              <div>
                <div class="fw-bold small" style="color:var(--primary)">98%</div>
                <div style="font-size:.68rem;color:#888">Client Satisfaction</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="scroll-indicator">
    <div class="scroll-mouse"></div>
    <span>Scroll</span>
  </div>
</section>

{{-- ═══════════════════ STATS RINGS ═══════════════════ --}}
<div class="container">
  <div class="stats-rings" data-aos="fade-up">
    <svg width="0" height="0" style="position:absolute"><defs>
      <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="var(--primary)"/><stop offset="100%" stop-color="var(--accent)"/>
      </linearGradient>
    </defs></svg>
    <div class="row g-4">
      @php $ringData = [
        ['icon'=>'ti ti-building','label'=>'Properties','key'=>'properties','color'=>'var(--accent)'],
        ['icon'=>'ti ti-handshake','label'=>'Deals Closed','key'=>'sold','color'=>'var(--gold)'],
        ['icon'=>'ti ti-briefcase','label'=>'Expert Agents','key'=>'agents','color'=>'#0f3460'],
        ['icon'=>'ti ti-users','label'=>'Happy Clients','key'=>'clients','color'=>'#2d6a4f'],
      ]; @endphp
      @foreach($ringData as $i => $r)
      <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="{{ $i * 100 }}">
        <div class="ring-card">
          <i class="ti {{ $r['icon'] }} ring-icon"></i>
          <div class="ring-wrap">
            <svg viewBox="0 0 100 100"><circle class="ring-bg" cx="50" cy="50" r="45"/><circle class="ring-fg" cx="50" cy="50" r="45" data-count="{{ $stats[$r['key']] }}" data-max="{{ max($stats[$r['key']], 1000) }}" style="stroke:{{ $r['color'] }}"/></svg>
            <div class="ring-num" data-count="{{ $stats[$r['key']] }}">0</div>
          </div>
          <div class="ring-label">{{ $r['label'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══════════════════ PROPERTY TYPES 3D ═══════════════════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-layout-grid"></i> Browse by Category
      </span>
      <h2 class="section-title">Property Types</h2>
      <p class="section-subtitle">Explore our diverse range of property categories</p>
    </div>
    <div class="row g-4">
      @php $types = [
        ['icon'=>'ti ti-home','label'=>'Houses','count'=>'120+','color'=>'#e94560','type'=>'house'],
        ['icon'=>'ti ti-building-community','label'=>'Flats','count'=>'80+','color'=>'#0f3460','type'=>'flat'],
        ['icon'=>'ti ti-map','label'=>'Plots','count'=>'200+','color'=>'#f5a623','type'=>'plot'],
        ['icon'=>'ti ti-building-skyscraper','label'=>'Commercial','count'=>'60+','color'=>'#1a1a2e','type'=>'commercial'],
        ['icon'=>'ti ti-tree','label'=>'Farmhouses','count'=>'40+','color'=>'#2d6a4f','type'=>'farmhouse'],
        ['icon'=>'ti ti-home-star','label'=>'Penthouse','count'=>'25+','color'=>'#7209b7','type'=>'penthouse'],
      ]; @endphp
      @foreach($types as $t)
      <div class="col-6 col-md-4 col-lg-2" data-aos="flip-up" data-aos-delay="{{ $loop->index * 60 }}">
        <div class="type-cube" style="--type-color:{{ $t['color'] }}">
          <a href="{{ route('website.properties', ['type' => $t['type']]) }}" class="type-cube-inner text-decoration-none d-flex flex-column align-items-center">
            <i class="ti {{ $t['icon'] }} cube-icon"></i>
            <h6 class="fw-bold mt-3 mb-0" style="color:#333;">{{ $t['label'] }}</h6>
            <span class="cube-count">{{ $t['count'] }} listings</span>
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ FEATURED PROPERTIES CAROUSEL ═══════════════════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5" data-aos="fade-up">
      <div>
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
          <i class="ti ti-star"></i> Hand-picked
        </span>
        <h2 class="section-title mb-0 lh-1"><span style="color:var(--accent);">Featured</span> <span style="font-weight:300;opacity:.7;">Properties</span></h2>
        <p class="text-secondary mt-1 mb-0" style="max-width:400px;">Curated selections of the finest properties available</p>
      </div>
      <div class="carousel-arrows flex-shrink-0">
        <button id="carouselPrev"><i class="ti ti-arrow-left"></i></button>
        <button id="carouselNext"><i class="ti ti-arrow-right"></i></button>
      </div>
    </div>
  </div>

  <div class="container-fluid px-0 overflow-hidden">
    <div class="properties-carousel px-4" id="propCarousel">
      @forelse($featuredProperties as $property)
      <div class="carousel-item-prop">
        <div class="prop-card">
          <div class="img-wrap">
            @php
              $img = $property->primaryMedia ?? $property->media->where('type','image')->first();
            @endphp
            @if($img)
            <img src="{{ Storage::url($img->file_path) }}" alt="{{ $property->title }}" loading="lazy">
            @else
            <div class="d-flex align-items-center justify-content-center bg-light text-secondary" style="height:220px;">
              <i class="ti ti-building" style="font-size:3rem;opacity:.25;"></i>
            </div>
            @endif
            <div class="img-shimmer"></div>
            <span class="badge-tag">{{ ucfirst($property->transaction_type) }}</span>
            <span class="price-floating">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'PKR' }}</span>
            <div class="view-btn">
              <a href="{{ route('website.property', $property) }}" class="btn btn-light btn-sm rounded-pill px-4 shadow-sm fw-semibold">
                <i class="ti ti-eye me-1"></i>Quick View
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span style="background:var(--primary);color:#fff;font-size:.62rem;padding:.2rem .65rem;border-radius:50px;font-weight:600;">
                <i class="ti ti-map-pin me-1"></i>{{ $property->city ?? 'N/A' }}
              </span>
              @if($property->possession_status)
              <span class="badge bg-dark" style="font-size:.58rem;font-weight:500;">{{ ucfirst(str_replace('_',' ',$property->possession_status)) }}</span>
              @endif
            </div>
            <h6 class="prop-title mb-1">{{ $property->title }}</h6>
            <p class="prop-location mb-2">{{ Str::limit($property->location_address ?? $property->sector_town ?? '', 55) }}</p>
            <div class="prop-meta pt-2 border-top">
              @if($property->bedrooms) <span><i class="ti ti-bed"></i> {{ $property->bedrooms }}</span> @endif
              @if($property->bathrooms) <span><i class="ti ti-droplet"></i> {{ $property->bathrooms }}</span> @endif
              @if($property->plot_size) <span><i class="ti ti-ruler"></i> {{ $property->plot_size }} {{ $property->plot_size_unit }}</span> @endif
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="text-center py-5" style="min-width:100%;">
        <i class="ti ti-building" style="font-size:4rem;opacity:.15;"></i>
        <p class="text-secondary mt-3">No featured properties yet.</p>
        <a href="{{ route('website.properties') }}" class="btn btn-accent mt-2">Browse All</a>
      </div>
      @endforelse
    </div>
  </div>

  @if($featuredProperties->count())
  <div class="text-center mt-4" data-aos="fade-up">
    <a href="{{ route('website.properties') }}" class="btn btn-accent btn-lg px-5 py-3 rounded-pill fw-bold">
      View All Properties <i class="ti ti-arrow-right ms-2"></i>
    </a>
  </div>
  @endif
</section>

{{-- ═══════════════════ HOW IT WORKS ═══════════════════ --}}
<section class="how-3d py-5">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-steps"></i> Simple Process
      </span>
      <h2 class="section-title">How It Works</h2>
      <p class="section-subtitle">Buy, sell, or rent in just 4 easy steps</p>
    </div>
    <div class="row how-3d-steps">
      @php $steps = [
        ['icon'=>'ti ti-search','title'=>'Search','desc'=>'Browse thousands of listings with smart filters.'],
        ['icon'=>'ti ti-calendar-event','title'=>'Visit','desc'=>'Schedule a site visit at your convenience.'],
        ['icon'=>'ti ti-file-description','title'=>'Deal','desc'=>'Expert negotiation &amp; documentation support.'],
        ['icon'=>'ti ti-home-check','title'=>'Possession','desc'=>'Legal assistance for a smooth handover.'],
      ]; @endphp
      @foreach($steps as $i => $s)
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="{{ $i * 120 }}">
        <div class="how-3d-step">
          <div class="step-ring">
            <svg viewBox="0 0 64 64"><circle class="ring-progress" cx="32" cy="32" r="27" data-progress="{{ ($i + 1) * 25 }}"/></svg>
            <span class="step-num">{{ $i + 1 }}</span>
          </div>
          <div style="font-size:1.4rem;color:var(--accent);margin-bottom:.5rem;"><i class="ti {{ $s['icon'] }}"></i></div>
          <h6>{{ $s['title'] }}</h6>
          <p>{{ $s['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ TESTIMONIALS ═══════════════════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-message-star"></i> Testimonials
      </span>
      <h2 class="section-title">What Our Clients Say</h2>
      <p class="section-subtitle">Real stories from real clients who trusted us</p>
    </div>

    @php $testimonials = [
      ['name'=>'Ahmed Khan','role'=>'Home Buyer','img'=>'https://i.pravatar.cc/100?u=1','text'=>'Exceptional service! Found my dream home within a week. The team was incredibly professional and guided me through every step of the purchase.','rating'=>5],
      ['name'=>'Sara Ali','role'=>'Property Investor','img'=>'https://i.pravatar.cc/100?u=2','text'=>'I\'ve used multiple platforms but this one stands out. Verified listings, honest agents, and zero hidden fees. Highly recommended for serious buyers.','rating'=>5],
      ['name'=>'Usman Raza','role'=>'Seller','img'=>'https://i.pravatar.cc/100?u=3','text'=>'Sold my property in just 2 weeks at a great price. The marketing was top-notch and they handled all the paperwork. Absolutely hassle-free experience!','rating'=>5],
      ['name'=>'Fatima Zafar','role'=>'Renter','img'=>'https://i.pravatar.cc/100?u=4','text'=>'Found the perfect apartment in just 3 days. The search filters made it so easy to find exactly what I needed. Great support throughout.','rating'=>5],
    ]; @endphp

    <div class="testi-carousel" data-aos="fade-up">
      <div class="testi-track" id="testiTrack">
        @foreach($testimonials as $t)
        <div class="testi-slide">
          <div class="testi-inner">
            <div class="quote-bg">"</div>
            <div class="testi-stars mb-3">
              @for($r=0;$r<$t['rating'];$r++) <i class="ti ti-star-filled"></i>@endfor
              @for($r=$t['rating'];$r<5;$r++) <i class="ti ti-star" style="opacity:.2"></i>@endfor
            </div>
            <p class="testi-text mb-4">"{{ $t['text'] }}"</p>
            <div class="d-flex align-items-center gap-3">
              <img src="{{ $t['img'] }}" alt="{{ $t['name'] }}" class="testi-avatar" loading="lazy">
              <div>
                <div class="fw-bold" style="font-size:.95rem;">{{ $t['name'] }}</div>
                <div style="font-size:.8rem;color:var(--text-muted);">{{ $t['role'] }}</div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="testi-dots" id="testiDots">
      @foreach($testimonials as $i => $t)
      <button class="testi-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ CITIES ═══════════════════ --}}
@if(isset($cities) && $cities->count())
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5" data-aos="fade-up">
      <div>
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
          <i class="ti ti-map-pins"></i> Explore
        </span>
        <h2 class="section-title mb-0">Cities We Serve</h2>
        <p class="text-secondary mt-1 mb-0">Find properties across Pakistan's major cities</p>
      </div>
    </div>
    <div class="row g-4">
      @php $cityImages = [
        'Islamabad' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&q=80',
        'Lahore' => 'https://images.unsplash.com/photo-1599571234909-29ed5d1021d4?w=400&q=80',
        'Karachi' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f9e6?w=400&q=80',
      ]; @endphp
      @foreach($cities as $city)
      <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
        <a href="{{ route('website.properties', ['city' => $city]) }}" class="city-card">
          <img src="{{ $cityImages[$city] ?? 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&q=80' }}" alt="{{ $city }}" loading="lazy">
          <div class="city-shade">
            <div class="city-name">{{ $city }}</div>
            <div class="city-count">{{ \App\Models\Property::where('city',$city)->whereIn('status',['available','pending'])->count() }} properties</div>
            <div class="city-explore">Explore <i class="ti ti-arrow-right ms-1"></i></div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════ WHY CHOOSE US ═══════════════════ --}}
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
        <i class="ti ti-star"></i> Why Choose Us
      </span>
      <h2 class="section-title">Why {{ config('app.name') }}?</h2>
      <p class="section-subtitle">We make property deals transparent, fast, and hassle-free</p>
    </div>
    <div class="row g-4">
      @php $features = [
        ['icon'=>'ti ti-shield-check','title'=>'Verified Properties','desc'=>'Every listing is verified for authenticity and accurate details.'],
        ['icon'=>'ti ti-users','title'=>'Expert Agents','desc'=>'Experienced agents guide you through every step of the process.'],
        ['icon'=>'ti ti-file-description','title'=>'Legal Support','desc'=>'Complete documentation and legal assistance for smooth transactions.'],
        ['icon'=>'ti ti-currency-dollar','title'=>'Best Prices','desc'=>'Get competitive prices with transparent fee structures.'],
        ['icon'=>'ti ti-clock','title'=>'Fast Processing','desc'=>'Quick turnaround from property search to final possession.'],
        ['icon'=>'ti ti-headset','title'=>'24/7 Support','desc'=>'Round-the-clock support for all your queries.'],
      ]; @endphp
      @foreach($features as $f)
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
        <div class="why-card h-100">
          <div class="why-icon"><i class="ti {{ $f['icon'] }}"></i></div>
          <h6>{{ $f['title'] }}</h6>
          <p>{{ $f['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="cta-wrap py-5">
  <div class="container" data-aos="fade-up">
    <div class="cta-inner">
      <div class="cta-glow"></div>
      <div class="row align-items-center g-4 position-relative" style="z-index:1;">
        <div class="col-lg-7 text-white">
          <span class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill" style="background:rgba(255,255,255,.1);font-size:.78rem;font-weight:600;backdrop-filter:blur(8px);">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
            Get Started Today
          </span>
          <h2 class="display-5 fw-bold mb-3">Ready to Find Your Perfect Property?</h2>
          <p style="color:rgba(255,255,255,.65);font-size:1.05rem;max-width:480px;">
            Get in touch with our team for a free consultation. Let us help you make the right move.
          </p>
        </div>
        <div class="col-lg-5 text-lg-end">
          <a href="{{ route('website.properties') }}" class="cta-btn cta-btn-primary me-2">
            <span class="btn-shimmer"></span>
            Browse Properties <i class="ti ti-arrow-right ms-1"></i>
          </a>
          <a href="{{ route('website.contact') }}" class="cta-btn cta-btn-outline mt-2 mt-lg-0">
            <i class="ti ti-headset me-1"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════ TRUST BAR ═══════════════════ --}}
<section class="trust-marquee py-4">
  <div class="container">
    <p class="text-center text-secondary small text-uppercase mb-3 fw-semibold" style="letter-spacing:.1em;">Trusted by industry leaders</p>
  </div>
  <div class="trust-track">
    @php $brands = [
      'Alpha Developers','Metro Construction','Prime Estate','Urban Living','Capital Realty',
      'Golden Homes','Skyline Builders','Sapphire Properties'
    ]; @endphp
    @for($x=0;$x<2;$x++) @foreach($brands as $b)
    <span class="trust-item"><i class="ti ti-building-skyscraper"></i> {{ $b }}</span>
    @endforeach @endfor
  </div>
</section>

{{-- ═══════════════════ CURSOR ═══════════════════ --}}
<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
@endsection

@push('scripts')
<script>
// ═══════════════════════ CURSOR ═══════════════════════
(function(){const d=document.getElementById('cursorDot'),r=document.getElementById('cursorRing');if(!d||!r)return;let mx=0,my=0;document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;d.style.opacity='1';r.style.opacity='1';d.style.left=mx+'px';d.style.top=my+'px';r.style.left=mx+'px';r.style.top=my+'px'});document.addEventListener('mouseleave',()=>{d.style.opacity='0';r.style.opacity='0'});document.querySelectorAll('a,button,.type-cube,.prop-card,.city-card,.why-card').forEach(el=>{el.addEventListener('mouseenter',()=>{d.style.width='14px';d.style.height='14px';r.style.width='56px';r.style.height='56px';r.style.borderColor='rgba(233,69,96,.6)'});el.addEventListener('mouseleave',()=>{d.style.width='8px';d.style.height='8px';r.style.width='40px';r.style.height='40px';r.style.borderColor='rgba(233,69,96,.4)'})})})();

// ═══════════════════════ WORD SCRAMBLE ═══════════════════════
(function(){const el=document.getElementById('scramble');if(!el)return;const words=['Dream Home','Luxury Living','Perfect Property','Smart Investment','Dream Villa'];let idx=0,frame=0,interval;function scramble(){const target=words[idx];let current=el.textContent;if(frame<target.length){let out='';for(let i=0;i<target.length;i++){if(i<frame)out+=target[i];else out+=String.fromCharCode(65+Math.random()*26)}el.textContent=out;frame++;setTimeout(scramble,50)}else{el.textContent=target;clearTimeout(interval);setTimeout(()=>{frame=0;idx=(idx+1)%words.length;scramble()},2500)}}scramble()})();

// ═══════════════════════ PARTICLES ═══════════════════════
(function(){const c=document.getElementById('particles');if(!c)return;for(let i=0;i<50;i++){const p=document.createElement('div');p.className='particle';p.style.left=Math.random()*100+'%';p.style.animationDuration=(8+Math.random()*12)+'s';p.style.animationDelay=Math.random()*10+'s';p.style.width=(2+Math.random()*3)+'px';p.style.height=p.style.width;c.appendChild(p)}})();

// ═══════════════════════ RING ANIMATION ═══════════════════════
(function(){const rings=document.querySelectorAll('.ring-fg');const nums=document.querySelectorAll('.ring-num[data-count]');if(!rings.length)return;let done=false;function animate(){if(done)return;const rect=rings[0].closest('.stats-rings').getBoundingClientRect();if(rect.top<window.innerHeight-80&&!done){done=true;rings.forEach((r,i)=>{const target=parseInt(r.dataset.count);const max=parseInt(r.dataset.max);if(isNaN(target))return;const offset=283-(target/max*283);r.style.strokeDashoffset=offset});nums.forEach(n=>{const t=parseInt(n.dataset.count);if(isNaN(t))return;let c=0;const s=Math.max(1,Math.floor(t/50));const iv=setInterval(()=>{c+=s;if(c>=t){c=t;clearInterval(iv)}n.textContent=c.toLocaleString()},20)})}}window.addEventListener('scroll',animate);animate()})();

// ═══════════════════════ HOW-IT-WORKS RINGS ═══════════════════════
(function(){const rings=document.querySelectorAll('.how-3d-step .ring-progress');if(!rings.length)return;const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){const ring=e.target;const p=parseInt(ring.dataset.progress);const off=170-(p/100*170);ring.style.strokeDashoffset=off;obs.unobserve(ring)}})});rings.forEach(r=>obs.observe(r))})();

// ═══════════════════════ TESTIMONIAL CAROUSEL ═══════════════════════
(function(){const track=document.getElementById('testiTrack');const dots=document.querySelectorAll('.testi-dot');if(!track||!dots.length)return;let cur=0,total=dots.length,interval;function go(i){if(i<0)i=total-1;if(i>=total)i=0;cur=i;track.style.transform='translateX(-'+(cur*100)+'%)';dots.forEach((d,j)=>d.classList.toggle('active',j===cur))}dots.forEach((d,i)=>d.addEventListener('click',()=>{go(i);reset()}));function reset(){clearInterval(interval);interval=setInterval(()=>go(cur+1),5000)}interval=setInterval(()=>go(cur+1),5000);document.querySelector('.testi-carousel').addEventListener('mouseenter',()=>clearInterval(interval));document.querySelector('.testi-carousel').addEventListener('mouseleave',()=>{clearInterval(interval);interval=setInterval(()=>go(cur+1),5000)})})();

// ═══════════════════════ PROPERTY CAROUSEL ═══════════════════════
(function(){const c=document.getElementById('propCarousel');const prev=document.getElementById('carouselPrev');const next=document.getElementById('carouselNext');if(!c||!prev||!next)return;const scrollAmt=400;prev.addEventListener('click',()=>c.scrollBy({left:-scrollAmt,behavior:'smooth'}));next.addEventListener('click',()=>c.scrollBy({left:scrollAmt,behavior:'smooth'}));let isDown=false,startX,sL;c.addEventListener('mousedown',e=>{isDown=true;c.style.cursor='grabbing';startX=e.pageX-c.offsetLeft;sL=c.scrollLeft});c.addEventListener('mouseleave',()=>{isDown=false;c.style.cursor='grab'});c.addEventListener('mouseup',()=>{isDown=false;c.style.cursor='grab'});c.addEventListener('mousemove',e=>{if(!isDown)return;e.preventDefault();const x=e.pageX-c.offsetLeft;c.scrollLeft=sL-(x-startX)*1.5});c.style.cursor='grab'})();

// ═══════════════════════ MAGNETIC BUTTON EFFECT ═══════════════════════
document.querySelectorAll('.cta-btn').forEach(btn=>{btn.addEventListener('mousemove',e=>{const r=btn.getBoundingClientRect(),x=e.clientX-r.left-r.width/2,y=e.clientY-r.top-r.height/2;btn.style.transform=`translate(${x*.15}px,${y*.15}px)`});btn.addEventListener('mouseleave',()=>btn.style.transform='')});

// ═══════════════════════ PARALLAX ═══════════════════════
window.addEventListener('scroll',()=>{const h=document.querySelector('.hero-buildings');if(!h)return;h.style.transform='translateY('+window.pageYOffset*.08+'px)'});
</script>
@endpush
