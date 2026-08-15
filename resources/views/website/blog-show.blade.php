@extends('layouts.website')

@section('title', $post->seo_title ?: $post->title)

@push('head')
@if($post->seo_description)
<meta name="description" content="{{ $post->seo_description }}">
@endif
@endpush

@section('content')
<section class="sky-section py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.blog') }}" class="text-decoration-none">Blog</a></li>
                <li class="breadcrumb-item active">{{ $post->title }}</li>
            </ol>
        </nav>

        <article class="sky-card card border-0 shadow-sm overflow-hidden">
            @if($post->featured_image)
                <div class="blog-hero" style="background-image:url('{{ Storage::url($post->featured_image) }}');">
                    <div class="blog-hero-overlay"></div>
                    <div class="blog-hero-content">
                        <div class="small mb-2 opacity-75">
                            <i class="ti ti-calendar-event"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                            @if($post->author) &middot; {{ $post->author->name }} @endif
                        </div>
                        <h1 class="blog-hero-title">{{ $post->title }}</h1>
                    </div>
                </div>
            @else
                <div class="card-body p-4 p-md-5 pb-0">
                    <div class="small text-amber mb-2">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                        @if($post->author) &middot; {{ $post->author->name }} @endif
                    </div>
                    <h1 class="sky-title mb-3">{{ $post->title }}</h1>
                </div>
            @endif
            <div class="card-body p-4 p-md-5">
                @if($post->excerpt)
                    <p class="text-secondary fst-italic mb-4">{{ $post->excerpt }}</p>
                @endif
                <div class="blog-body" style="line-height:1.9;">{!! nl2br(e($post->body)) !!}</div>
            </div>
        </article>

        @if($related->isNotEmpty())
            <h3 class="mt-5 mb-3 fw-semibold">Related Articles <span class="urdu">(متعلقہ مضامین)</span></h3>
            <div class="row g-4">
                @foreach($related as $r)
                <div class="col-md-4">
                    <article class="card sky-card blog-card h-100 border-0 shadow-sm">
                        @if($r->featured_image)
                            <a href="{{ route('website.blog.show', $r) }}">
                                <img src="{{ Storage::url($r->featured_image) }}" class="card-img-top" alt="{{ $r->title }}" style="height:160px;object-fit:cover;">
                            </a>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{ route('website.blog.show', $r) }}" class="text-decoration-none text-dark">{{ $r->title }}</a>
                            </h6>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('website.blog') }}" class="btn btn-outline-secondary"><i class="ti ti-arrow-left"></i> Back to Blog <span class="urdu">(واپس)</span></a>
        </div>
    </div>
</section>
@endsection
