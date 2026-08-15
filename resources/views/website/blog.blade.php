@extends('layouts.website')

@section('title', 'Blog')

@section('content')
<section class="sky-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="sky-title">Blog <span class="urdu">(بلاگ)</span></h1>
            <p class="text-secondary">Market insights, news and guides from our real estate experts across Pakistan.</p>
        </div>

        @if($posts->isEmpty())
            <div class="empty-state">
                <i class="ti ti-article"></i>
                <p>No articles published yet. <span class="urdu">(ابھی تک کوئی مضمون نہیں)</span></p>
            </div>
        @else
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <article class="card sky-card blog-card h-100 border-0 shadow-sm">
                        @if($post->featured_image)
                            <a href="{{ route('website.blog.show', $post) }}">
                                <img src="{{ Storage::url($post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
                            </a>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="small text-amber mb-2">{{ $post->published_at ? $post->published_at->format('d M Y') : '' }}</div>
                            <h5 class="card-title">
                                <a href="{{ route('website.blog.show', $post) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                            </h5>
                            @if($post->excerpt)
                                <p class="text-secondary flex-grow-1">{{ \Illuminate\Support\Str::limit($post->excerpt, 140) }}</p>
                            @endif
                            <a href="{{ route('website.blog.show', $post) }}" class="btn btn-link text-amber px-0">Read more <i class="ti ti-arrow-right"></i></a>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>

            @if($posts->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $posts->withQueryString()->links() }}
            </div>
            @endif
        @endif
    </div>
</section>
@endsection
