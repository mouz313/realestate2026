@extends('layouts.admin')

@section('title', 'Edit Post <span class="urdu">(پوسٹ میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Blog <span class="urdu">(بلاگ)</span></a></li>
        <li class="breadcrumb-item active">Edit <span class="urdu">(ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-article"></i> <span class="urdu">(پوسٹ میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="urdu">(عنوان)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $post->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excerpt <span class="urdu">(خلاصہ)</span></label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" rows="2" maxlength="2000">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Body <span class="urdu">(متن)</span> <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="12" required>{{ old('body', $post->body) }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Featured Image <span class="urdu">(تصویر)</span></label>
                        @if($post->featured_image)
                            <div class="mb-2">
                                <img src="{{ route('posts.image', $post) }}" alt="" class="img-fluid rounded" style="max-height:140px;object-fit:cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" name="featured_image" accept="image/*">
                        <small class="text-secondary">JPEG, PNG, WebP up to 5MB. Auto-compressed on upload.</small>
                        @error('featured_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Publish <span class="urdu">(شائع کریں)</span></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublished" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublished">Published</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Publish Date <span class="urdu">(تاریخ)</span></label>
                        <input type="date" class="form-control @error('published_at') is-invalid @enderror" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d') : '') }}">
                        @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <hr>
                    <h6 class="mb-2">SEO <span class="urdu">(تلاش)</span></h6>
                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input type="text" class="form-control @error('seo_title') is-invalid @enderror" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" maxlength="255">
                        @error('seo_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea class="form-control @error('seo_description') is-invalid @enderror" name="seo_description" rows="3" maxlength="500">{{ old('seo_description', $post->seo_description) }}</textarea>
                        @error('seo_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> <span class="urdu">(اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('posts.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>
@endsection
