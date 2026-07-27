@extends('layouts.website')

@section('title', 'Terms of Service — Skyline Real Estate')
@section('meta_description', 'Terms and conditions for using our real estate platform. Understand your rights and obligations.')

@section('content')
<section class="page-hero-creative" data-hero-label="TERMS">
    <div class="container position-relative">
        <div class="mb-3">
            <span class="icon-box-circle glow-pulse"><i class="ti ti-file-text"></i></span>
        </div>
        <h1 class="anim-fade-up anim-delay-1">Terms of <span class="text-amber">Service</span></h1>
        <p class="lead anim-fade-up anim-delay-2">Please read these terms carefully before using our platform</p>
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
                    <h5><i class="ti ti-check text-amber me-2"></i>Acceptance of Terms</h5>
                    <p>By accessing or using {{ config('app.name') }}, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you may not use our services.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-1">
                    <div class="step-num">2</div>
                    <h5><i class="ti ti-building text-amber me-2"></i>Services Description</h5>
                    <p>{{ config('app.name') }} provides a platform connecting property buyers, sellers, tenants, and landlords. We facilitate property listings, inquiries, visits, and related real estate services. We do not guarantee the accuracy of third-party listings.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-2">
                    <div class="step-num">3</div>
                    <h5><i class="ti ti-user-check text-amber me-2"></i>User Obligations</h5>
                    <p>You agree to provide accurate information when using our services, to not misuse the platform for fraudulent purposes, and to comply with all applicable laws. You are responsible for maintaining the confidentiality of your account credentials.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-3">
                    <div class="step-num">4</div>
                    <h5><i class="ti ti-home text-amber me-2"></i>Property Listings</h5>
                    <p>Property owners and agents are responsible for the accuracy of their listings. {{ config('app.name') }} reserves the right to remove listings that violate our policies or applicable laws. We do not conduct independent verification of all listed properties.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up">
                    <div class="step-num">5</div>
                    <h5><i class="ti ti-alert-triangle text-amber me-2"></i>Limitation of Liability</h5>
                    <p>{{ config('app.name') }} shall not be liable for any direct, indirect, incidental, or consequential damages resulting from your use of our platform. We provide services on an "as is" basis without warranties of any kind.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-1">
                    <div class="step-num">6</div>
                    <h5><i class="ti ti-copyright text-amber me-2"></i>Intellectual Property</h5>
                    <p>All content, trademarks, and intellectual property on this website are owned by {{ config('app.name') }} or its licensors. You may not reproduce, distribute, or create derivative works without prior written consent.</p>
                </div>

                <div class="step-card mb-4 anim-fade-up anim-delay-2">
                    <div class="step-num">7</div>
                    <h5><i class="ti ti-player-stop text-amber me-2"></i>Termination</h5>
                    <p>We reserve the right to suspend or terminate access to our services for violations of these terms, fraudulent activity, or any other reason at our discretion.</p>
                </div>

                <div class="step-card anim-fade-up anim-delay-3">
                    <div class="step-num">8</div>
                    <h5><i class="ti ti-scale text-amber me-2"></i>Governing Law</h5>
                    <p>These terms shall be governed by and construed in accordance with the laws of Pakistan. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of Islamabad.</p>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
