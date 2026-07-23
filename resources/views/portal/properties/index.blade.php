@extends('portal.layouts.app')

@section('title', 'Properties')

@section('content')
<h4 class="mb-4">Available Properties</h4>

@if($properties->isEmpty())
<div class="card shadow">
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-building" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0">No properties available yet.</p>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($properties as $property)
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            @if($property->primaryMedia)
            <img src="{{ asset('storage/' . $property->primaryMedia->file_path) }}" class="card-img-top" alt="{{ $property->title }}" style="height:200px;object-fit:cover;">
            @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                <i class="ti ti-building text-secondary" style="font-size:3rem;"></i>
            </div>
            @endif
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="mb-0">{{ $property->title }}</h5>
                    @php
                        $tb = ['residential' => 'primary', 'commercial' => 'info', 'land' => 'success', 'industrial' => 'warning'];
                    @endphp
                    <span class="badge bg-{{ $tb[$property->type] ?? 'secondary' }}">{{ ucfirst($property->type) }}</span>
                </div>
                <p class="fs-5 fw-bold mb-2">{{ number_format($property->price, 2) }}</p>
                <p class="text-secondary small mb-2"><i class="ti ti-map-pin"></i> {{ $property->city }}{{ $property->sector_town ? ', ' . $property->sector_town : '' }}</p>
                <div class="d-flex gap-3 text-secondary small mb-3">
                    @if($property->bedrooms)
                    <span><i class="ti ti-bed"></i> {{ $property->bedrooms }}</span>
                    @endif
                    @if($property->bathrooms)
                    <span><i class="ti ti-bath"></i> {{ $property->bathrooms }}</span>
                    @endif
                    @if($property->plot_size)
                    <span><i class="ti ti-ruler"></i> {{ $property->plot_size }} {{ $property->plot_size_unit ?? 'sqft' }}</span>
                    @endif
                </div>
                <a href="{{ route('portal.properties.show', $property) }}" class="btn btn-sm btn-outline-dark mt-auto">View Details</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($properties->hasPages())
<div class="mt-3">{{ $properties->links() }}</div>
@endif
@endif
@endsection
