@props([
    'icon' => 'ti-inbox',
    'title' => 'No records found',
    'urdu' => null,
    'actionUrl' => null,
    'actionLabel' => null,
    'colspan' => 6,
])

<td colspan="{{ $colspan }}">
    <div class="empty-state">
        <i class="ti {{ $icon }}"></i>
        <p>{{ $title }} @if($urdu)<span class="urdu">({{ $urdu }})</span>@endif</p>
        @if($actionUrl && $actionLabel)
            <a href="{{ $actionUrl }}" class="text-decoration-none fw-medium">{{ $actionLabel }}</a>
        @endif
    </div>
</td>
