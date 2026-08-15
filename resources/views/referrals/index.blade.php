@extends('layouts.admin')

@section('title', 'Referrals <span class="urdu">(ریفرلز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Referrals <span class="urdu">(ریفرلز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Referrals <span class="urdu">(ریفرلز)</span></h3>
        <div class="page-header-sub">{{ $referrals->total() }} <span class="urdu">(کل)</span></div>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Referrer <span class="urdu">(بھیجنے والا)</span></th>
                    <th>Referred <span class="urdu">(بھیجا گیا)</span></th>
                    <th>Contact <span class="urdu">(رابطہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th>Notes <span class="urdu">(نوٹس)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $referral)
                <tr>
                    <td class="fw-semibold">{{ $referral->referrer_name ?? '-' }}</td>
                    <td>{{ $referral->referred_name }}</td>
                    <td class="text-secondary">
                        {{ $referral->referred_phone ?? '-' }}<br>
                        <small>{{ $referral->referred_email ?? '' }}</small>
                    </td>
                    <td>
                        <span class="badge {{ $referral->status === 'converted' ? 'status-active' : ($referral->status === 'pending' ? 'status-pending' : 'status-draft') }}">{{ ucfirst($referral->status) }}</span>
                    </td>
                    <td class="text-secondary" style="max-width:240px;">{{ Str::limit($referral->notes, 100) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ti ti-users-group"></i>
                            <p>No referrals yet. <span class="urdu">(ابھی تک کوئی ریفرل نہیں)</span></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($referrals->hasPages())
    <div class="p-3 border-top">
        {{ $referrals->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
