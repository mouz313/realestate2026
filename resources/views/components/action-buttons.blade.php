@props([
    'show' => ['view', 'edit', 'delete'],
    'viewUrl' => null,
    'editUrl' => null,
    'deleteUrl' => null,
    'whatsappPhone' => null,
    'whatsappMsg' => null,
])

<div {{ $attributes->merge(['class' => 'action-btns flex-nowrap']) }}>
    @if(in_array('whatsapp', $show) && $whatsappPhone)
        <a href="{{ \App\Helpers\WhatsApp::shareLink($whatsappPhone, $whatsappMsg ?? 'Assalam o Alaikum, regarding your enquiry...') }}"
           target="_blank"
           class="btn btn-sm btn-success"
           title="WhatsApp">
            <i class="ti ti-brand-whatsapp"></i>
        </a>
    @endif

    @if(in_array('view', $show) && $viewUrl)
        <a href="{{ $viewUrl }}" class="btn btn-sm btn-outline-secondary" title="View">
            <i class="ti ti-eye"></i>
        </a>
    @endif

    @if(in_array('edit', $show) && $editUrl)
        <a href="{{ $editUrl }}" class="btn btn-sm btn-outline-secondary" title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endif

    @if(in_array('delete', $show) && $deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                <i class="ti ti-trash"></i>
            </button>
        </form>
    @endif

    {{ $slot }}
</div>
