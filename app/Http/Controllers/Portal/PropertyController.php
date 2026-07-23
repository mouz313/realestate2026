<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Setting;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with(['owner', 'primaryMedia'])->where('status', 'available')->latest()->paginate(12);

        return view('portal.properties.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::with(['owner', 'media', 'documents', 'assignedAgent'])->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('portal.properties.show', compact('property', 'settings'));
    }
}
