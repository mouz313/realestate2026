@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Notifications</h3>
        <button type="button" id="markAll" class="btn btn-sm btn-outline-secondary">Mark all as read</button>
    </div>
    <div class="card-body p-0">
        @forelse ($notifications as $notification)
            <div class="d-flex px-3 py-2 align-items-center border-bottom {{$notification->unread() ? 'table-active fw-bold' : ''}}">
                <div class="flex-grow-1">
                    {{ $notification->data['title'] ?? 'Notification' }}
                    @if($notification->data['message'] ?? '')
                        <div class="small text-muted">{{$notification->data['message']}}</div>
                    @endif
                </div>
                <small class="text-muted mb-0">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <div class="p-3 text-muted">No notifications yet.</div>
        @endforelse
    </div>
    @if ($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('markAll')?.addEventListener('click', function () {
    fetch('{{ route('notifications.mark-read') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams({ _all: '1' })
    });
});
</script>
@endpush