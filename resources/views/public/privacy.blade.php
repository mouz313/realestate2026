@extends('public.layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Privacy Policy of ' . config('app.name') . '. Learn how we collect, use, and protect your personal information.')
@section('meta_keywords', 'privacy policy, data protection, personal information, real estate privacy')

@section('content')
<section class="page-banner">
  <div class="container">
    <h1 class="text-white">Privacy <span style="color:var(--accent);">Policy</span></h1>
    <p>How we collect, use, and protect your information</p>
  </div>
</section>

<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="mb-5">
          <h5 class="fw-bold">1. Information We Collect</h5>
          <p class="text-secondary">We collect information you provide directly, such as your name, email address, phone number, and property preferences when you fill out forms, contact us, or register on our platform. We also automatically collect certain technical information including your IP address, browser type, and usage patterns.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">2. How We Use Your Information</h5>
          <p class="text-secondary">Your information is used to provide and improve our real estate services, process your inquiries, send property recommendations, communicate with you about listings and visits, and comply with legal obligations. We do not sell your personal data to third parties.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">3. Data Sharing</h5>
          <p class="text-secondary">We may share your information with property owners, agents, and relevant service providers to facilitate transactions you have requested. We may also disclose information when required by law or to protect our rights.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">4. Data Security</h5>
          <p class="text-secondary">We implement industry-standard security measures including encryption, secure socket layer (SSL) technology, and regular security audits to protect your personal information from unauthorized access, alteration, or disclosure.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">5. Your Rights</h5>
          <p class="text-secondary">You have the right to access, correct, or delete your personal data held by us. You may also opt out of marketing communications at any time. To exercise these rights, please contact us using the information provided on our Contact page.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">6. Cookies</h5>
          <p class="text-secondary">Our website uses cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie preferences through your browser settings.</p>
        </div>

        <div>
          <h5 class="fw-bold">7. Contact Us</h5>
          <p class="text-secondary">If you have any questions about this Privacy Policy, please contact us at {{ $contactInfo['email'] ?? 'info@example.com' }} or call {{ $contactInfo['phone'] ?? '+92 300 1234567' }}.</p>
        </div>

        <hr class="my-4">
        <p class="small text-secondary"><em>Last updated: {{ date('F d, Y') }}</em></p>
      </div>
    </div>
  </div>
</section>
@endsection
