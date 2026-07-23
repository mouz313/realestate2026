@extends('layouts.admin')
@section('title', 'Reports <span class="urdu">(رپورٹس)</span>')
@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Reports <span class="urdu">(رپورٹس)</span></li>
    </ol>
</nav>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h3><i class="ti ti-report me-1"></i> Reports & Analytics <span class="urdu">(رپورٹس اور تجزیہ)</span></h3>
        <div class="page-header-sub">View and export business intelligence reports <span class="urdu">(کاروباری ذہانت کی رپورٹس دیکھیں اور برآمد کریں)</span></div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card" style="--accent-clients: #6366f1;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Sales Report <span class="urdu">(فروخت رپورٹ)</span></div>
                        <p class="text-secondary small mb-2">Monthly sales volume, commissions, deal count <span class="urdu">(ماہانہ فروخت حجم، کمیشن، ڈیل کی تعداد)</span></p>
                        <a href="{{ route('reports.sales') }}" class="btn btn-sm btn-dark">View Report <span class="urdu">(رپورٹ دیکھیں)</span></a>
                    </div>
                    <i class="ti ti-trending-up" style="font-size:2rem;opacity:0.15;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card" style="--accent-clients: #ec4899;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Agent Performance <span class="urdu">(ایجنٹ کارکردگی)</span></div>
                        <p class="text-secondary small mb-2">Deals closed, volume, commission per agent <span class="urdu">(ڈیلیں بند، حجم، فی ایجنٹ کمیشن)</span></p>
                        <a href="{{ route('reports.agent-performance') }}" class="btn btn-sm btn-dark">View Report <span class="urdu">(رپورٹ دیکھیں)</span></a>
                    </div>
                    <i class="ti ti-users" style="font-size:2rem;opacity:0.15;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card" style="--accent-clients: #3b82f6;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Commission Report <span class="urdu">(کمیشن رپورٹ)</span></div>
                        <p class="text-secondary small mb-2">Pending, approved, paid commissions <span class="urdu">(زیر التواء، منظور شدہ، ادا شدہ کمیشن)</span></p>
                        <a href="{{ route('reports.commissions') }}" class="btn btn-sm btn-dark">View Report <span class="urdu">(رپورٹ دیکھیں)</span></a>
                    </div>
                    <i class="ti ti-percentage" style="font-size:2rem;opacity:0.15;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card" style="--accent-clients: #f59e0b;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Rent Roll <span class="urdu">(کرایہ رول)</span></div>
                        <p class="text-secondary small mb-2">Active rentals, deposits, monthly income <span class="urdu">(فعال کرایہ، جمع رقم، ماہانہ آمدنی)</span></p>
                        <a href="{{ route('reports.rent-roll') }}" class="btn btn-sm btn-dark">View Report <span class="urdu">(رپورٹ دیکھیں)</span></a>
                    </div>
                    <i class="ti ti-home-2" style="font-size:2rem;opacity:0.15;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection