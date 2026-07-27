@extends('layouts.website')

@section('title', 'Privacy Policy — Skyline Real Estate')
@section('meta_description', 'Privacy Policy — Learn how we collect, use, and protect your personal information.')

@section('content')
<section class="page-hero-creative" data-hero-label="PRIVACY">
    <div class="container position-relative">
        <div class="mb-3">
            <span class="icon-box-circle glow-pulse"><i class="ti ti-shield-lock"></i></span>
        </div>
        <h1 class="anim-fade-up anim-delay-1">Privacy <span class="text-amber">Policy</span></h1>
        <p class="lead anim-fade-up anim-delay-2">How we collect, use, and protect your personal information</p>
        <div class="mt-3 anim-fade-up anim-delay-3">
            <span class="text-secondary small"><i class="ti ti-calendar me-1 text-amber"></i> Last updated: {{ date('F d, Y') }}</span>
        </div>
    </div>
</section>

<section class="section-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="step-card mb-4 anim-fade-up">
                    <div class="step-num">1</div>
                    <h5><i class="ti ti-database text-amber me-2"></i>Information We Collect</h5>
                    <p>We collect information you provide directly, such as your name, email address, phone number, and property preferences when you fill out forms, contact us, or register on our platform. We also automatically collect certain technical information including your IP address, browser type, and usage patterns.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-1">
                    <div class="step-num">2</div>
                    <h5><i class="ti ti-settings text-amber me-2"></i>How We Use Your Information</h5>
                    <p>Your information is used to provide and improve our real estate services, process your inquiries, send property recommendations, communicate with you about listings and visits, and comply with legal obligations. We do not sell your personal data to third parties.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-2">
                    <div class="step-num">3</div>
                    <h5><i class="ti ti-users text-amber me-2"></i>Data Sharing</h5>
                    <p>We may share your information with property owners, agents, and relevant service providers to facilitate transactions you have requested. We may also disclose information when required by law or to protect our rights.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-3">
                    <div class="step-num">4</div>
                    <h5><i class="ti ti-lock text-amber me-2"></i>Data Security</h5>
                    <p>We implement industry-standard security measures including encryption, secure socket layer (SSL) technology, and regular security audits to protect your personal information from unauthorized access, alteration, or disclosure.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up">
                    <div class="step-num">5</div>
                    <h5><i class="ti ti-user-check text-amber me-2"></i>Your Rights</h5>
                    <p>You have the right to access, correct, or delete your personal data held by us. You may also opt out of marketing communications at any time. To exercise these rights, please contact us using the information provided on our Contact page.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-1">
                    <div class="step-num">6</div>
                    <h5><i class="ti ti-cookie text-amber me-2"></i>Cookies</h5>
                    <p>Our website uses cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie preferences through your browser settings.</p>
                </div>

                <div class="step-card anim-fade-up anim-delay-2">
                    <div class="step-num">7</div>
                    <h5><i class="ti ti-mail text-amber me-2"></i>Contact Us</h5>
                    <p>If you have any questions about this Privacy Policy, please contact us at <strong class="text-amber">{{ $contactInfo['email'] ?? 'info@example.com' }}</strong> or call <strong class="text-amber">{{ $contactInfo['phone'] ?? '+92 300 1234567' }}</strong>.</p>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
