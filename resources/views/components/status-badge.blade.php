@props([
    'status' => '',
    'color' => null,    // optional override: primary|success|warning|danger|info|secondary|light|dark
    'size' => 'sm',     // sm | md
])

@php
    $colors = [
        'new' => 'primary',
        'contacted' => 'info',
        'callback' => 'warning',
        'matched' => 'secondary',
        'converted' => 'success',
        'closed_won' => 'success',
        'closed_lost' => 'danger',
        'lost' => 'danger',
        'available' => 'success',
        'sold' => 'dark',
        'rented' => 'info',
        'booked' => 'warning',
        'inactive' => 'secondary',
        'pending' => 'warning',
        'approved' => 'info',
        'paid' => 'success',
        'cancelled' => 'danger',
        'overdue' => 'danger',
        'partial' => 'warning',
        'completed' => 'success',
        'active' => 'success',
        'inactive_user' => 'secondary',
    ];
    $bg = $color ?? ($colors[$status] ?? 'light');
    $textOnDark = in_array($bg, ['light', 'warning', 'info']) ? 'text-dark' : 'text-white';
@endphp

<span {{ $attributes->merge(['class' => "badge bg-{$bg} {$textOnDark}" . ($size === 'sm' ? '' : ' fs-6')]) }}>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
