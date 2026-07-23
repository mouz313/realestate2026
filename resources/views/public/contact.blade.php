@extends('public.layouts.app')

@section('title', 'Contact Us')
@section('meta_description', 'Get in touch with ' . config('app.name') . '. Call, email, or visit our office in Islamabad. Our team is ready to assist you with all your real estate needs.')
@section('meta_keywords', 'contact, real estate inquiry, Islamabad office, property consultation')

@push('styles')
<style>
.contact-section { background: var(--section-bg); }
.contact-card { border: none; border-radius: 16px; padding: 1.5rem; background: #fff; box-shadow: var(--card-shadow); display: flex; align-items: center; gap: 1rem; }
.contact-card .icon-circle { width: 48px; height: 48px; border-radius: 50%; background: rgba(233,69,96,.1); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.25rem; flex-shrink: 0; }
.contact-card h6 { font-weight: 700; margin-bottom: .15rem; }
.contact-card p { font-size: .88rem; color: var(--text-muted); margin-bottom: 0; }
.map-placeholder { border-radius: 16px; overflow: hidden; height: 350px; background: var(--section-bg); display: flex; align-items: center; justify-content: center; position: relative; }
</style>
@endpush

@section('content')
<section class="page-banner">
  <div class="container">
    <h1 class="text-white">Contact <span style="color:var(--accent);">Us</span></h1>
    <p>We're here to help with all your real estate needs</p>
  </div>
</section>

<section class="contact-section py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="mb-4">
          <span class="d-inline-flex align-items-center gap-2 text-accent fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-message"></i> Get in Touch</span>
          <h2 class="fw-bold mt-2">Let's Talk About Your Property Needs</h2>
          <p class="text-secondary">Fill out the form and our team will get back to you within 24 hours.</p>
        </div>

        <div class="d-flex flex-column gap-3">
          <div class="contact-card">
            <div class="icon-circle"><i class="ti ti-map-pin"></i></div>
            <div>
              <h6>Office Address</h6>
              <p>{{ $settings['address'] ?? 'Islamabad, Pakistan' }}</p>
            </div>
          </div>
          <div class="contact-card">
            <div class="icon-circle"><i class="ti ti-phone"></i></div>
            <div>
              <h6>Phone</h6>
              <p><a href="tel:{{ $settings['phone'] ?? '+92 300 1234567' }}" class="text-decoration-none text-dark">{{ $settings['phone'] ?? '+92 300 1234567' }}</a></p>
            </div>
          </div>
          <div class="contact-card">
            <div class="icon-circle"><i class="ti ti-mail"></i></div>
            <div>
              <h6>Email</h6>
              <p><a href="mailto:{{ $settings['email'] ?? 'info@example.com' }}" class="text-decoration-none text-dark">{{ $settings['email'] ?? 'info@example.com' }}</a></p>
            </div>
          </div>
          <div class="contact-card">
            <div class="icon-circle"><i class="ti ti-clock"></i></div>
            <div>
              <h6>Working Hours</h6>
              <p>{{ $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <form action="{{ route('website.contact.submit') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small">Phone</label>
                  <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small">Subject</label>
                  <select class="form-select form-select-lg @error('subject') is-invalid @enderror" name="subject">
                    <option value="">Select a subject</option>
                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                    <option value="Buying" {{ old('subject') == 'Buying' ? 'selected' : '' }}>Buying</option>
                    <option value="Selling" {{ old('subject') == 'Selling' ? 'selected' : '' }}>Selling</option>
                    <option value="Renting" {{ old('subject') == 'Renting' ? 'selected' : '' }}>Renting</option>
                    <option value="Schedule a Visit" {{ old('subject') == 'Schedule a Visit' ? 'selected' : '' }}>Schedule a Visit</option>
                  </select>
                  @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">Message <span class="text-danger">*</span></label>
                  <textarea class="form-control form-control-lg @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
                  @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-accent btn-lg px-5 fw-bold rounded-pill">
                    <i class="ti ti-send me-2"></i>Send Message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5">
      <div class="map-placeholder">
        @if(($settings['map_lat'] ?? false) && ($settings['map_lng'] ?? false))
        <iframe src="https://www.google.com/maps?q={{ $settings['map_lat'] }},{{ $settings['map_lng'] }}&z=14&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
        @else
        <div class="text-center text-secondary">
          <i class="ti ti-map-2" style="font-size:4rem;opacity:.2;display:block;margin-bottom:1rem;"></i>
          <p class="mb-0">{{ $settings['address'] ?? 'Islamabad, Pakistan' }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
