@extends('public.layouts.app')

@section('title', 'Terms of Service')
@section('meta_description', 'Terms and conditions for using ' . config('app.name') . ' real estate platform. Understand your rights and obligations.')
@section('meta_keywords', 'terms of service, terms and conditions, real estate terms, property listing terms')

@section('content')
<section class="page-banner">
  <div class="container">
    <h1 class="text-white">Terms of <span style="color:var(--accent);">Service</span></h1>
    <p>Please read these terms carefully before using our platform</p>
  </div>
</section>

<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="mb-5">
          <h5 class="fw-bold">1. Acceptance of Terms</h5>
          <p class="text-secondary">By accessing or using {{ config('app.name') }}, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you may not use our services.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">2. Services Description</h5>
          <p class="text-secondary">{{ config('app.name') }} provides a platform connecting property buyers, sellers, tenants, and landlords. We facilitate property listings, inquiries, visits, and related real estate services. We do not guarantee the accuracy of third-party listings.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">3. User Obligations</h5>
          <p class="text-secondary">You agree to provide accurate information when using our services, to not misuse the platform for fraudulent purposes, and to comply with all applicable laws. You are responsible for maintaining the confidentiality of your account credentials.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">4. Property Listings</h5>
          <p class="text-secondary">Property owners and agents are responsible for the accuracy of their listings. {{ config('app.name') }} reserves the right to remove listings that violate our policies or applicable laws. We do not conduct independent verification of all listed properties.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">5. Limitation of Liability</h5>
          <p class="text-secondary">{{ config('app.name') }} shall not be liable for any direct, indirect, incidental, or consequential damages resulting from your use of our platform. We provide services on an "as is" basis without warranties of any kind.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">6. Intellectual Property</h5>
          <p class="text-secondary">All content, trademarks, and intellectual property on this website are owned by {{ config('app.name') }} or its licensors. You may not reproduce, distribute, or create derivative works without prior written consent.</p>
        </div>

        <div class="mb-5">
          <h5 class="fw-bold">7. Termination</h5>
          <p class="text-secondary">We reserve the right to suspend or terminate access to our services for violations of these terms, fraudulent activity, or any other reason at our discretion.</p>
        </div>

        <div>
          <h5 class="fw-bold">8. Governing Law</h5>
          <p class="text-secondary">These terms shall be governed by and construed in accordance with the laws of Pakistan. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of Islamabad.</p>
        </div>

        <hr class="my-4">
        <p class="small text-secondary"><em>Last updated: {{ date('F d, Y') }}</em></p>
      </div>
    </div>
  </div>
</section>
@endsection
