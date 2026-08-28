@props([
    'title' => '',
    'subtitle' => null,
    'urdu' => null,
    'icon' => null,
])

<div class="page-header flex-wrap gap-2">
    <div>
            <h3 class="d-flex align-items-center gap-2">
                @if($icon)<i class="ti {{ $icon }} text-muted"></i>@endif
                <span>{{ $title }}</span>
            </h3>
        @if($subtitle)
            <div class="page-header-sub">{{ $subtitle }}</div>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="d-flex gap-2 align-items-center flex-wrap">
            {{ $slot }}
        </div>
    @endif
</div>
