<?php

namespace App\Exports;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyExport
{
    public static function build(): array
    {
        $agentId = Auth::user()->isAgent() ? Auth::user()->agent_id : null;

        $query = Property::with(['owner', 'assignedAgent'])
            ->when($agentId, fn ($q) => $q->where('assigned_agent_id', $agentId))
            ->latest();

        $headers = ['Code', 'Title', 'Type', 'Status', 'Price', 'City', 'Sector/Town', 'Bedrooms', 'Bathrooms', 'Plot Size', 'Agent', 'Owner'];

        $rows = $query->get()->map(function ($p) {
            return [
                $p->property_code ?? $p->id,
                $p->title,
                str_replace('_', ' ', $p->category),
                str_replace('_', ' ', $p->status),
                $p->price,
                $p->city,
                $p->sector_town,
                $p->bedrooms,
                $p->bathrooms,
                $p->plot_size ? $p->plot_size.' '.$p->plot_size_unit : '',
                $p->assignedAgent?->name ?? '',
                $p->owner?->name ?? '',
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
