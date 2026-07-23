<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\City;
use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyMedia;
use App\Models\PropertyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $properties = Property::with(['owner', 'assignedAgent'])
            ->when($agentId, fn($q) => $q->where('assigned_agent_id', $agentId))
            ->latest()->paginate(15);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $types = ['house', 'flat', 'plot', 'commercial', 'farmhouse', 'penthouse'];
        $transactionTypes = ['sale', 'rent', 'lease'];
        $statuses = ['available', 'under_offer', 'sold', 'rented', 'under_construction', 'off_market'];
        $lastProperty = Property::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastProperty ? $lastProperty->id + 1 : 1;
        $autoCode = 'PR-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        return view('properties.create', compact('clients', 'agents', 'cities', 'types', 'transactionTypes', 'statuses', 'autoCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:house,flat,plot,commercial,farmhouse,penthouse',
            'transaction_type' => 'required|string|in:sale,rent,lease',
            'status' => 'required|string|in:available,under_offer,sold,rented,under_construction,off_market',
            'possession_status' => 'nullable|string|in:ready,under_construction,off_plan',
            'possession_year' => 'nullable|integer|min:1900|max:2100',
            'price' => 'required|numeric|min:0',
            'price_per_sqft' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'location_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'sector_town' => 'nullable|string|max:100',
            'block' => 'nullable|string|max:50',
            'plot_size' => 'nullable|numeric|min:0',
            'plot_size_unit' => 'nullable|string|max:20',
            'land_area' => 'nullable|numeric|min:0',
            'covered_area' => 'nullable|numeric|min:0',
            'covered_area_unit' => 'nullable|string|max:20',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'kitchens' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor_number' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'furnished' => 'nullable|boolean',
            'parking_spaces' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*' => 'string',
            'building_features' => 'nullable|array',
            'building_features.*' => 'string',
            'community_amenities' => 'nullable|array',
            'community_amenities.*' => 'string',
            'communication_features' => 'nullable|array',
            'communication_features.*' => 'string',
            'nearby_landmarks' => 'nullable|string|max:1000',
            'nearby_places' => 'nullable|array',
            'nearby_places.*' => 'string|max:50',
            'utilities' => 'nullable|array',
            'utilities.*' => 'string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string|max:5000',
            'owner_id' => 'required|exists:clients,id',
            'assigned_agent_id' => 'nullable|exists:agents,id',
            'listed_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
        ]);

        $data = $request->except(['images', 'video']);
        $data['features'] = $request->has('features') ? array_map('trim', explode(',', $request->features)) : null;
        $data['additional_rooms'] = $request->has('additional_rooms') ? $request->additional_rooms : null;
        $data['building_features'] = $request->has('building_features') ? $request->building_features : null;
        $data['community_amenities'] = $request->has('community_amenities') ? $request->community_amenities : null;
        $data['communication_features'] = $request->has('communication_features') ? $request->communication_features : null;
        $data['nearby_places'] = $request->has('nearby_places') ? $request->nearby_places : null;
        $data['utilities'] = $request->has('utilities') ? $request->utilities : null;
        $data['city_id'] = $request->city ? City::where('name', $request->city)->value('id') : null;

        $data['property_code'] = DB::transaction(function () {
            $last = Property::withTrashed()->lockForUpdate()->orderBy('id', 'desc')->first();
            $nextId = $last ? $last->id + 1 : 1;
            return 'PR-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });

        $property = Property::create($data);
        $this->handleMediaUploads($request, $property);
        toastr()->success('Property added successfully.');
        return redirect()->route('properties.index');
    }

    public function show(Property $property)
    {
        $this->authorizePropertyAccess($property);
        $property->load(['owner', 'assignedAgent', 'media', 'documents']);
        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorizePropertyAccess($property);
        $property->load('media');
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $types = ['house', 'flat', 'plot', 'commercial', 'farmhouse', 'penthouse'];
        $transactionTypes = ['sale', 'rent', 'lease'];
        $statuses = ['available', 'under_offer', 'sold', 'rented', 'under_construction', 'off_market'];
        return view('properties.edit', compact('property', 'clients', 'agents', 'cities', 'types', 'transactionTypes', 'statuses'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizePropertyAccess($property);
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:house,flat,plot,commercial,farmhouse,penthouse',
            'transaction_type' => 'required|string|in:sale,rent,lease',
            'status' => 'required|string|in:available,under_offer,sold,rented,under_construction,off_market',
            'possession_status' => 'nullable|string|in:ready,under_construction,off_plan',
            'possession_year' => 'nullable|integer|min:1900|max:2100',
            'price' => 'required|numeric|min:0',
            'price_per_sqft' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'location_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'sector_town' => 'nullable|string|max:100',
            'block' => 'nullable|string|max:50',
            'plot_size' => 'nullable|numeric|min:0',
            'plot_size_unit' => 'nullable|string|max:20',
            'land_area' => 'nullable|numeric|min:0',
            'covered_area' => 'nullable|numeric|min:0',
            'covered_area_unit' => 'nullable|string|max:20',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'kitchens' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor_number' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'furnished' => 'nullable|boolean',
            'parking_spaces' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*' => 'string',
            'building_features' => 'nullable|array',
            'building_features.*' => 'string',
            'community_amenities' => 'nullable|array',
            'community_amenities.*' => 'string',
            'communication_features' => 'nullable|array',
            'communication_features.*' => 'string',
            'nearby_landmarks' => 'nullable|string|max:1000',
            'nearby_places' => 'nullable|array',
            'nearby_places.*' => 'string|max:50',
            'utilities' => 'nullable|array',
            'utilities.*' => 'string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string|max:5000',
            'owner_id' => 'required|exists:clients,id',
            'assigned_agent_id' => 'nullable|exists:agents,id',
            'listed_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
        ]);

        $data = $request->except(['images', 'video']);
        $data['features'] = $request->has('features') ? array_map('trim', explode(',', $request->features)) : null;
        $data['additional_rooms'] = $request->has('additional_rooms') ? $request->additional_rooms : null;
        $data['building_features'] = $request->has('building_features') ? $request->building_features : null;
        $data['community_amenities'] = $request->has('community_amenities') ? $request->community_amenities : null;
        $data['communication_features'] = $request->has('communication_features') ? $request->communication_features : null;
        $data['nearby_places'] = $request->has('nearby_places') ? $request->nearby_places : null;
        $data['utilities'] = $request->has('utilities') ? $request->utilities : null;
        $data['city_id'] = $request->city ? City::where('name', $request->city)->value('id') : null;

        $property->update($data);
        $this->handleMediaUploads($request, $property);
        toastr()->success('Property updated successfully.');
        return redirect()->route('properties.index');
    }

    public function destroy(Property $property)
    {
        $this->authorizePropertyAccess($property);
        foreach ($property->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
        $property->delete();
        toastr()->success('Property deleted successfully.');
        return redirect()->route('properties.index');
    }

    public function setPrimary(PropertyMedia $media)
    {
        $property = $media->property;
        $property->media()->where('type', 'image')->update(['is_primary' => false]);
        $media->update(['is_primary' => true]);
        toastr()->success('Primary image updated.');
        return back();
    }

    public function destroyMedia(PropertyMedia $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
        toastr()->success('Media deleted.');
        return back();
    }

    private function handleMediaUploads(Request $request, Property $property)
    {
        if ($request->hasFile('images')) {
            $sortOrder = $property->media()->max('sort_order') ?? 0;
            $hasPrimary = $property->media()->where('type', 'image')->where('is_primary', true)->exists();

            foreach ($request->file('images') as $file) {
                $path = $file->store('property-media', 'public');
                $sortOrder++;
                PropertyMedia::create([
                    'property_id' => $property->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'is_primary' => !$hasPrimary && $sortOrder === 1,
                    'sort_order' => $sortOrder,
                ]);
            }
        }

        if ($request->hasFile('video')) {
            $hasVideo = $property->media()->where('type', 'video')->exists();
            if (!$hasVideo) {
                $path = $request->file('video')->store('property-media', 'public');
                $sortOrder = $property->media()->max('sort_order') ?? 0;
                PropertyMedia::create([
                    'property_id' => $property->id,
                    'type' => 'video',
                    'file_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $sortOrder + 1,
                ]);
            }
        }
    }
}
