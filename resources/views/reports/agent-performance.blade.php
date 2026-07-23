@extends('layouts.admin')
@section('title', 'Agent Performance <span class="urdu">(ایجنٹ کارکردگی)</span>')
@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports <span class="urdu">(رپورٹس)</span></a></li>
        <li class="breadcrumb-item active">Agent Performance <span class="urdu">(ایجنٹ کارکردگی)</span></li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h3 class="mb-0"><i class="ti ti-users me-1"></i> Agent Performance <span class="urdu">(ایجنٹ کارکردگی)</span></h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small">From <span class="urdu">(سے)</span></label>
                <input type="date" name="start" class="form-control" value="{{ $start }}">
            </div>
            <div class="col-auto">
                <label class="form-label small">To <span class="urdu">(تک)</span></label>
                <input type="date" name="end" class="form-control" value="{{ $end }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark">Filter <span class="urdu">(فلٹر)</span></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap gap-2"><h5 class="mb-0">Agent Rankings <span class="urdu">(ایجنٹ کی درجہ بندی)</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                        <th class="d-none d-sm-table-cell">Phone <span class="urdu">(فون)</span></th>
                        <th class="text-end">Deals <span class="urdu">(ڈیلیں)</span></th>
                        <th class="text-end d-none d-sm-table-cell">Volume <span class="urdu">(حجم)</span></th>
                        <th class="text-end d-none d-md-table-cell">Commission Earned <span class="urdu">(کمیشن حاصل)</span></th>
                        <th class="text-center d-none d-md-table-cell">Rating <span class="urdu">(درجہ بندی)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $i => $a)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $a['name'] }}</td>
                        <td class="d-none d-sm-table-cell">{{ $a['phone'] }}</td>
                        <td class="text-end">{{ $a['deals_count'] }}</td>
                        <td class="text-end d-none d-sm-table-cell">{{ number_format($a['total_volume'], 0) }}</td>
                        <td class="text-end text-success d-none d-md-table-cell">{{ number_format($a['commission_earned'], 0) }}</td>
                        <td class="text-center d-none d-md-table-cell">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="ti ti-star{{ $s <= $a['rating'] ? '' : '-off' }}" style="color: {{ $s <= $a['rating'] ? 'gold' : '#ccc' }};"></i>
                            @endfor
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3">No active agents found. <span class="urdu">(کوئی فعال ایجنٹ نہیں ملا)</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection