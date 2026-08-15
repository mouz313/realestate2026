@extends('layouts.admin')

@section('title', 'Reviews <span class="urdu">(رائے)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Reviews <span class="urdu">(رائے)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Reviews <span class="urdu">(رائے)</span></h3>
        <div class="page-header-sub">{{ $reviews->total() }} <span class="urdu">(کل)</span></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('reviews.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4 col-sm-6">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="ti ti-filter"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Reviewer <span class="urdu">(نے لکھا)</span></th>
                    <th>Rating <span class="urdu">(درجہ)</span></th>
                    <th>Comment <span class="urdu">(تاثر)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td class="fw-semibold">{{ $review->property->title ?? '-' }}</td>
                    <td>{{ $review->name }}<br><small class="text-secondary">{{ $review->email ?? '' }}</small></td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star{{ $i <= $review->rating ? '-filled text-warning' : '' }}"></i>
                        @endfor
                    </td>
                    <td class="text-secondary" style="max-width:280px;">{{ Str::limit($review->comment, 120) }}</td>
                    <td>
                        @if($review->approved)
                            <span class="badge status-active">Approved</span>
                        @else
                            <span class="badge status-draft">Pending</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            @if(! $review->approved)
                                <form action="{{ route('reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="ti ti-check"></i></button>
                                </form>
                            @endif
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ti ti-star"></i>
                            <p>No reviews yet. <span class="urdu">(ابھی تک کوئی رائے نہیں)</span></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div class="p-3 border-top">
        {{ $reviews->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
