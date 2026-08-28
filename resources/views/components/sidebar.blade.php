@php
    use Illuminate\Support\Facades\Route as RouteFacade;
@endphp

@foreach($groups as $gIdx => $group)
    @php
        $showGroup = true;
        if (!empty($group['permission'])) {
            $showGroup = auth()->user()?->can($group['permission']) ?? false;
        }
    @endphp

    @if($showGroup)
        @if(!empty($group['label']))
            <div class="sidebar-nav-group-label">
                {{ $group['label'] }}
                @if(!empty($group['urdu']))<span class="urdu">({{ $group['urdu'] }})</span>@endif
            </div>
        @endif

        @foreach($group['items'] as $item)
            @php
                $showItem = true;
                if (!empty($item['permission'])) {
                    $showItem = auth()->user()?->can($item['permission']) ?? false;
                }
                $url = RouteFacade::has($item['route']) ? route($item['route']) : '#';
            @endphp

            @if($showItem)
                <a href="{{ $url }}" class="sidebar-nav-item {{ ($item['active'] ?? false) ? 'active' : '' }}">
                    <i class="ti {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['urdu']))
                        <span class="sidebar-nav-urdu urdu">{{ $item['urdu'] }}</span>
                    @endif
                    @if(!empty($item['badge']) && $item['badge'] > 0)
                        <span class="sidebar-nav-badge">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach

        @if($gIdx < count($groups) - 1)
            <div class="sidebar-section-divider"></div>
        @endif
    @endif
@endforeach
