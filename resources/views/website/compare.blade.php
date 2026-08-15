@extends('layouts.website')

@section('title', 'Compare Properties')

@section('content')
<section class="sky-section py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="sky-title">Compare Properties <span class="urdu">(جائیدادوں کا موازنہ)</span></h1>
            <p class="text-secondary">Side-by-side comparison of selected properties (up to 4).</p>
        </div>

        @if($properties->isEmpty())
            <div class="empty-state">
                <i class="ti ti-git-compare"></i>
                <p>No properties selected to compare. <span class="urdu">(موازنہ کے لیے کوئی جائیداد منتخب نہیں)</span></p>
                <a href="{{ route('website.properties') }}" class="btn btn-amber mt-3">Browse Listings</a>
            </div>
        @else
            <div class="compare-grid">
                @foreach($properties as $property)
                    <div class="compare-card sky-card">
                        @if($property->primaryMedia)
                            <img src="{{ asset('storage/' . $property->primaryMedia->file_path) }}" class="compare-card-img" alt="{{ $property->title }}">
                        @else
                            <div class="compare-card-img property-img-placeholder"><i class="ti ti-building"></i></div>
                        @endif

                        <div class="compare-card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <a href="{{ route('website.property', $property) }}" class="fw-semibold text-decoration-none text-dark">{{ $property->title }}</a>
                                <a href="{{ route('website.compare.remove', $property) }}" class="text-danger text-decoration-none" title="Remove"><i class="ti ti-x"></i></a>
                            </div>

                            <ul class="spec-list mt-3">
                                @foreach($property->comparisonSpecs() as $spec)
                                    <li>
                                        <i class="ti {{ $spec['icon'] }}"></i>
                                        <span class="spec-label">{{ $spec['label'] }}</span>
                                        <span class="spec-value">{{ $spec['value'] }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('website.property', $property) }}" class="btn btn-amber btn-sm w-100 mt-3">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('website.properties') }}" class="btn btn-outline-secondary">Add more</a>
            </div>
        @endif
    </div>
</section>
@endsection
