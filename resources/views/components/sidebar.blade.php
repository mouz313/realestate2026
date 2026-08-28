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
            @php
                $collapseId = $uid . '-group-' . $gIdx;
            @endphp
            <button type="button" class="sidebar-group-toggle" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="true">
                <span class="label-text">{{ $group['label'] }}
                    @if(!empty($group['urdu']))<span class="urdu">({{ $group['urdu'] }})</span>@endif
                </span>
                <i class="ti ti-chevron-down sidebar-group-caret"></i>
            </button>
            <div class="collapse show" id="{{ $collapseId }}">
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
            </div>
            @if($gIdx < count($groups) - 1)
                <div class="sidebar-section-divider"></div>
            @endif
        @else
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
    @endif
@endforeach
