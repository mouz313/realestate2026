@extends('layouts.admin')

@section('title', 'Blog Posts <span class="urdu">(بلاگ پوسٹس)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Blog <span class="urdu">(بلاگ)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Blog Posts <span class="urdu">(بلاگ پوسٹس)</span></h3>
        <div class="page-header-sub">{{ $posts->total() }} <span class="urdu">(کل)</span></div>
    </div>
    <a href="{{ route('posts.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> <span class="urdu">(نئی پوسٹ)</span>
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('posts.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5 col-sm-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 col-sm-6">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="ti ti-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title <span class="urdu">(عنوان)</span></th>
                    <th class="d-none d-md-table-cell">Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Published <span class="urdu">(تاریخ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($post->featured_image)
                                <img src="{{ route('posts.image', $post) }}" alt="" class="rounded" style="width:46px;height:46px;object-fit:cover;">
                            @endif
                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate">{{ $post->title }}</div>
                                <a href="{{ route('website.blog.show', $post) }}" target="_blank" class="small text-secondary text-decoration-none">/blog/{{ $post->slug }}</a>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($post->is_published)
                            <span class="badge status-active">Published</span>
                        @else
                            <span class="badge status-draft">Draft</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-secondary">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('website.blog.show', $post) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View on website">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="ti ti-article"></i>
                            <p>No posts yet. <span class="urdu">(ابھی تک کوئی پوسٹ نہیں)</span></p>
                            <a href="{{ route('posts.create') }}" class="text-decoration-none fw-medium">Write your first post <span class="urdu">(پہلی پوسٹ لکھیں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
    <div class="p-3 border-top">
        {{ $posts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
