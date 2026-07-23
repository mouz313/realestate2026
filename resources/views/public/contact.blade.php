@extends('public.layouts.app')

@section('title', 'Contact Us')

@push('styles')
<style>
/* ═══════════════════════ BANNER ═══════════════════════ */
.contact-banner{
  min-height:45vh;display:flex;align-items:center;position:relative;overflow:hidden;
  background:linear-gradient(135deg,var(--primary) 0%,#0f3460 50%,var(--primary-light) 100%);
}
.contact-banner .mesh-bg{position:absolute;inset:0;overflow:hidden}
.contact-banner .mesh-bg .blob{
  position:absolute;border-radius:50%;filter:blur(80px);opacity:.25;
  animation:blobMove 18s ease-in-out infinite alternate;
}
.contact-banner .mesh-bg .blob:nth-child(1){width:350px;height:350px;background:var(--accent);right:10%;top:-5%;animation-duration:20s}
.contact-banner .mesh-bg .blob:nth-child(2){width:280px;height:280px;background:var(--gold);left:-5%;bottom:-5%;animation-duration:16s;animation-delay:-5s}
@keyframes blobMove{
  0%{transform:translate(0,0) scale(1) rotate(0deg)}
  33%{transform:translate(30px,-20px) scale(1.1) rotate(120deg)}
  66%{transform:translate(-20px,30px) scale(.9) rotate(240deg)}
  100%{transform:translate(20px,-15px) scale(1) rotate(360deg)}
}

/* ═══════════════════════ INFO CARDS ═══════════════════════ */
.info-card-3d{perspective:600px;height:100%}
.info-card-inner{
  background:#fff;border-radius:20px;padding:1.75rem;box-shadow:var(--card-shadow);
  display:flex;align-items:center;gap:1rem;transition:all .4s;height:100%;position:relative;overflow:hidden;
}
.info-card-3d:hover .info-card-inner{transform:rotateX(4deg) translateY(-6px);box-shadow:0 16px 48px rgba(0,0,0,.1)}
.info-card-inner .ic-icon{
  width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;color:#fff;flex-shrink:0;
  background:linear-gradient(135deg,var(--primary),var(--accent));transition:all .4s;
}
.info-card-3d:hover .ic-icon{transform:scale(1.1) rotate(-5deg);border-radius:50%}
.info-card-inner h6{font-weight:700;margin-bottom:2px}
.info-card-inner p{font-size:.85rem;color:var(--text-muted);margin-bottom:0}

/* ═══════════════════════ FORM ═══════════════════════ */
.form-card{
  background:#fff;border-radius:24px;padding:2.5rem;box-shadow:0 8px 40px rgba(0,0,0,.06);
  position:relative;overflow:hidden;
}
.form-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:4px;
  background:linear-gradient(90deg,var(--primary),var(--accent),var(--gold));
}
.form-card .form-title{font-weight:800;font-size:1.4rem;margin-bottom:.25rem}
.form-group{position:relative;margin-bottom:1.25rem}
.form-group .form-control,.form-group .form-select{
  border:2px solid #eef0f4;border-radius:14px;padding:.85rem 1.25rem;font-size:.92rem;
  transition:all .3s;background:#fafbfc;
}
.form-group .form-control:focus,.form-group .form-select:focus{
  border-color:var(--accent);box-shadow:0 0 0 4px rgba(233,69,96,.1);background:#fff;
}
.form-group .form-floating-label{
  position:absolute;left:14px;top:14px;font-size:.82rem;color:#aaa;font-weight:500;
  pointer-events:none;transition:all .2s;background:#fafbfc;padding:0 4px;
}
.form-group .form-control:focus~.form-floating-label,
.form-group .form-control:not(:placeholder-shown)~.form-floating-label,
.form-group .form-select:focus~.form-floating-label,
.form-group .form-select:not([value=""]):valid~.form-floating-label{
  top:-10px;left:12px;font-size:.7rem;color:var(--accent);background:#fff;
}
.form-group .form-control.is-invalid{border-color:#dc3545}
.form-group .form-control.is-invalid~.form-floating-label{color:#dc3545}

.btn-submit{
  background:linear-gradient(135deg,var(--accent),#d63851);color:#fff;border:none;
  border-radius:14px;padding:.9rem 2.5rem;font-weight:700;font-size:1rem;
  transition:all .3s;position:relative;overflow:hidden;width:100%;
}
.btn-submit:hover{transform:scale(1.02);color:#fff;box-shadow:0 8px 30px rgba(233,69,96,.35)}
.btn-submit .btn-shimmer{
  position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
  transform:translateX(-100%);transition:transform .6s;
}
.btn-submit:hover .btn-shimmer{transform:translateX(100%)}

/* ═══════════════════════ MAP ═══════════════════════ */
.map-container{
  border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.08);
  height:300px;position:relative;background:var(--section-bg);
  display:flex;align-items:center;justify-content:center;
}
.map-container .map-pin{
  width:48px;height:48px;border-radius:50%;background:var(--accent);color:#fff;
  display:flex;align-items:center;justify-content:center;font-size:1.3rem;
  box-shadow:0 4px 20px rgba(233,69,96,.4);animation:pinPulse 2s ease-in-out infinite;
  position:relative;z-index:2;
}
.map-container .map-pin::after{
  content:'';position:absolute;width:80px;height:80px;border-radius:50%;
  background:rgba(233,69,96,.15);animation:pinRipple 2s ease-in-out infinite;
}
@keyframes pinPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.1)}}
@keyframes pinRipple{0%{transform:scale(.5);opacity:1}100%{transform:scale(2);opacity:0}}
</style>
@endpush

@section('content')
{{-- ═══════════════════ BANNER ═══════════════════ --}}
<section class="contact-banner">
  <div class="mesh-bg"><div class="blob"></div><div class="blob"></div></div>
  <div class="container position-relative" style="z-index:1;">
    <div class="row justify-content-center text-center text-white" data-aos="fade-up">
      <div class="col-lg-8">
        <span class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill" style="background:rgba(233,69,96,.15);font-size:.8rem;font-weight:600;backdrop-filter:blur(8px);border:1px solid rgba(233,69,96,.25);">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite"></span>
          Get in Touch
        </span>
        <h1 style="font-size:clamp(2rem,4vw,3rem);font-weight:800;letter-spacing:-1px;">Contact Us</h1>
        <p style="color:rgba(255,255,255,.65);font-size:1.05rem;max-width:500px;margin:.75rem auto 0;">Have a question or want to schedule a visit? We'd love to hear from you.</p>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════ CONTENT ═══════════════════ --}}
<section class="py-5" style="background:var(--section-bg);">
  <div class="container">
    <div class="row g-5">
      {{-- LEFT: Info + Map --}}
      <div class="col-lg-5" data-aos="fade-right">
        <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase mb-2" style="letter-spacing:.12em;">
          <i class="ti ti-info-circle"></i> Reach Us
        </span>
        <h2 class="display-6 fw-bold mb-1" style="color:var(--primary);letter-spacing:-.5px;font-size:2rem;">Let's Talk</h2>
        <p class="text-secondary mb-4">Our team is ready to assist you. Choose the way that works best.</p>

        <div class="d-flex flex-column gap-3 mb-4">
          @php $infos = [
            ['icon'=>'ti ti-map-pin','title'=>'Office Address','desc'=>'Islamabad, Pakistan'],
            ['icon'=>'ti ti-phone','title'=>'Phone','desc'=>'+92 300 1234567'],
            ['icon'=>'ti ti-mail','title'=>'Email','desc'=>'info@example.com'],
            ['icon'=>'ti ti-clock','title'=>'Working Hours','desc'=>'Mon-Sat: 9AM - 7PM'],
          ]; @endphp
          @foreach($infos as $info)
          <div class="info-card-3d" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
            <div class="info-card-inner">
              <div class="ic-icon"><i class="ti {{ $info['icon'] }}"></i></div>
              <div>
                <h6>{{ $info['title'] }}</h6>
                <p>{{ $info['desc'] }}</p>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="map-container" data-aos="fade-up" data-aos-delay="200">
          <div class="text-center">
            <div class="map-pin"><i class="ti ti-map-pin"></i></div>
            <div class="mt-2 fw-semibold" style="color:var(--primary);">Islamabad, Pakistan</div>
            <div class="small text-secondary">Visit our office</div>
          </div>
        </div>
      </div>

      {{-- RIGHT: Form --}}
      <div class="col-lg-7" data-aos="fade-left">
        <div class="form-card">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="ti ti-send" style="color:var(--accent);font-size:1.3rem;"></i>
            <span class="form-title">Send Us a Message</span>
          </div>
          <p class="text-secondary small mb-4">Fill in the details and we'll get back to you within 24 hours.</p>

          <form action="{{ route('website.contact.submit') }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder=" ">
                  <label class="form-floating-label">Full Name *</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder=" ">
                  <label class="form-floating-label">Email Address *</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder=" ">
                  <label class="form-floating-label">Phone Number</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <select class="form-select" name="subject">
                    <option value=""></option>
                    <option value="general">General Inquiry</option>
                    <option value="buying">Buying Property</option>
                    <option value="selling">Selling Property</option>
                    <option value="renting">Renting Property</option>
                    <option value="visit">Schedule a Visit</option>
                  </select>
                  <label class="form-floating-label">Subject</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="5" required placeholder=" ">{{ old('message') }}</textarea>
                  <label class="form-floating-label">Your Message *</label>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-submit">
                  <span class="btn-shimmer"></span>
                  <i class="ti ti-send me-1"></i>Send Message
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
