@extends('layouts.website')

@section('title', 'Contact Us — Skyline Real Estate')
@section('meta_description', 'Get in touch with us. Call, email, or visit our office. Our team is ready to assist you with all your real estate needs.')

@section('content')
<section class="page-hero">
    <div class="container position-relative">
        <h1>Contact <span class="text-amber">Us</span></h1>
        <p>We're here to help with all your real estate needs</p>
    </div>
</section>

<section class="section-dark">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="mb-4">
                    <span class="text-amber fw-semibold small text-uppercase" style="letter-spacing:.12em;"><i class="ti ti-message me-1"></i> Get in Touch</span>
                    <h2 class="fw-bold mt-2">Let's Talk About Your Property Needs</h2>
                    <p class="text-secondary">Fill out the form and our team will get back to you within 24 hours.</p>
                </div>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background:rgba(212,162,78,0.05);border:1px solid rgba(212,162,78,0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:rgba(212,162,78,0.1);">
                            <i class="ti ti-map-pin text-amber" style="font-size:1.25rem;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Office Address</h6>
                            <p class="small text-secondary mb-0">{{ $contactInfo['address'] ?? 'Islamabad, Pakistan' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background:rgba(212,162,78,0.05);border:1px solid rgba(212,162,78,0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:rgba(212,162,78,0.1);">
                            <i class="ti ti-phone text-amber" style="font-size:1.25rem;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Phone</h6>
                            <p class="small text-secondary mb-0"><a href="tel:{{ $contactInfo['phone'] ?? '+92 300 1234567' }}" class="text-decoration-none text-secondary">{{ $contactInfo['phone'] ?? '+92 300 1234567' }}</a></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background:rgba(212,162,78,0.05);border:1px solid rgba(212,162,78,0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:rgba(212,162,78,0.1);">
                            <i class="ti ti-mail text-amber" style="font-size:1.25rem;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Email</h6>
                            <p class="small text-secondary mb-0"><a href="mailto:{{ $contactInfo['email'] ?? 'info@example.com' }}" class="text-decoration-none text-secondary">{{ $contactInfo['email'] ?? 'info@example.com' }}</a></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background:rgba(212,162,78,0.05);border:1px solid rgba(212,162,78,0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:rgba(212,162,78,0.1);">
                            <i class="ti ti-clock text-amber" style="font-size:1.25rem;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Working Hours</h6>
                            <p class="small text-secondary mb-0">{{ $contactInfo['hours'] ?? 'Mon-Sat: 9AM - 7PM' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="p-4 rounded" style="background:var(--sky-bg-alt);border:1px solid rgba(212,162,78,0.1);">
                    <form action="{{ route('website.contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Subject</label>
                                <select class="form-select @error('subject') is-invalid @enderror" name="subject">
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
                                <label class="form-label small fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-amber px-5">
                                    <i class="ti ti-send me-1"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-5 rounded overflow-hidden" style="height:350px;background:var(--sky-bg-alt);border:1px solid rgba(212,162,78,0.1);">
            @if(($contactInfo['map_lat'] ?? false) && ($contactInfo['map_lng'] ?? false))
                <iframe src="https://www.google.com/maps?q={{ $contactInfo['map_lat'] }},{{ $contactInfo['map_lng'] }}&z=14&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
            @else
                <div class="d-flex align-items-center justify-content-center h-100 text-secondary">
                    <div class="text-center">
                        <i class="ti ti-map-2" style="font-size:4rem;opacity:.2;display:block;margin-bottom:1rem;"></i>
                        <p class="mb-0">{{ $contactInfo['address'] ?? 'Islamabad, Pakistan' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
