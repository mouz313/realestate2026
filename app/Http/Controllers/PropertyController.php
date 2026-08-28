<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\CallLog;
use App\Models\City;
use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyMedia;
use App\Models\Setting;
use App\Notifications\PropertyStatusChanged;
use App\Http\Requests\PropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $properties = Property::with(['owner', 'assignedAgent'])
            ->when($agentId, fn ($q) => $q->where('assigned_agent_id', $agentId))
            ->latest()->paginate(15);

        return view('properties.index', compact('properties'));
    }

    public function available()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $properties = Property::with(['owner', 'assignedAgent'])
            ->when($agentId, fn ($q) => $q->where('assigned_agent_id', $agentId))
            ->available()
            ->latest()->paginate(15);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $types = ['house', 'plot', 'farmhouse', 'agricultural_land', 'flat', 'studio_apartment', 'office', 'shop'];
        $transactionTypes = ['sale', 'buy', 'rent', 'installment'];
        $statuses = ['available', 'rented', 'sold'];
        $autoCode = \App\Services\SequenceService::property();

        $prefill = [];
        if ($callLog = CallLog::find(request('call_log'))) {
            $prefill = [
                'call_log_id' => $callLog->id,
                'owner_name' => $callLog->caller_role === 'seller' ? $callLog->name : null,
                'owner_phone' => $callLog->caller_role === 'seller' ? $callLog->phone : null,
                'category' => $callLog->category,
                'transaction_type' => $callLog->transaction_type === 'buy' ? 'sale' : $callLog->transaction_type,
                'city_id' => $callLog->city_id,
                'city' => $callLog->city,
                'location' => $callLog->location,
                'bedrooms' => $callLog->bedrooms,
                'budget_min' => $callLog->budget_min,
                'budget_max' => $callLog->budget_max,
            ];
        }

        return view('properties.create', compact('clients', 'agents', 'cities', 'types', 'transactionTypes', 'statuses', 'autoCode', 'prefill'));
    }

    public function store(PropertyRequest $request)
    {
        $data = $request->validated();
        $data['furnished'] = in_array($request->input('furnished_type'), ['furnished', 'semi_furnished']);

        // Empty form inputs arrive as "" and, after validation, may be cast to null.
        // NOT NULL numeric columns with a DB default (kitchens => 1, parking_spaces => 0,
        // price => 0) must keep that default; every other empty field becomes null.
        $notNullDefaults = ['kitchens' => 1, 'parking_spaces' => 0, 'price' => 0];
        foreach ($data as $k => $v) {
            if ($v === '' || $v === null) {
                $data[$k] = $notNullDefaults[$k] ?? null;
            }
        }
        $data['features'] = $request->has('features') ? array_map('trim', explode(',', $request->features)) : null;
        $data['additional_rooms'] = $request->has('additional_rooms') ? $request->additional_rooms : null;
        $data['building_features'] = $request->has('building_features') ? $request->building_features : null;
        $data['community_amenities'] = $request->has('community_amenities') ? $request->community_amenities : null;
        $data['communication_features'] = $request->has('communication_features') ? $request->communication_features : null;
        $data['nearby_places'] = $request->has('nearby_places') ? $request->nearby_places : null;
        $data['utilities'] = $request->has('utilities') ? $request->utilities : null;
        $data['city_id'] = $request->city ? City::where('name', $request->city)->value('id') : null;

        if (auth()->user()->isAgent() && ! $request->filled('assigned_agent_id')) {
            $data['assigned_agent_id'] = auth()->user()->agent_id;
        }

        // An agent who brings a property is recorded as the sourcing agent (context only).
        if (auth()->user()->isAgent()) {
            $data['sourced_by_agent_id'] = auth()->user()->agent_id;
        }

        // Conflict-of-interest guard: only admins may set the commission rate.
        if (! auth()->user()->isAdmin() && $request->filled('commission_rate')) {
            unset($data['commission_rate']);
        }

        $data['owner_id'] = Client::resolveOrCreate(
            $request->input('owner_id'),
            $request->input('owner_name'),
            $request->input('owner_phone'),
            'seller'
        );

        $data['property_code'] = \App\Services\SequenceService::property();

        $property = Property::create($data);
        $this->handleMediaUploads($request, $property);

        if ($callLogId = $request->input('call_log_id')) {
            CallLog::where('id', $callLogId)->update(['property_id' => $property->id, 'status' => 'matched', 'matched_at' => now()]);
        }

        toastr()->success('Property added successfully.');

        return redirect()->route('properties.index');
    }

    public function show(Property $property)
    {
        $this->authorize('update', $property);
        $property->load(['owner', 'assignedAgent', 'media', 'documents']);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('properties.show', compact('property', 'settings'));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        $property->load('media');
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $types = ['house', 'plot', 'farmhouse', 'agricultural_land', 'flat', 'studio_apartment', 'office', 'shop'];
        $transactionTypes = ['sale', 'buy', 'rent', 'installment'];
        $statuses = ['available', 'rented', 'sold'];

        return view('properties.edit', compact('property', 'clients', 'agents', 'cities', 'types', 'transactionTypes', 'statuses'));
    }

    public function update(PropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);
        $data = $request->validated();
        $data['furnished'] = in_array($request->input('furnished_type'), ['furnished', 'semi_furnished']);

        // Empty form inputs arrive as "" and, after validation, may be cast to null.
        // NOT NULL numeric columns with a DB default (kitchens => 1, parking_spaces => 0,
        // price => 0) must keep that default; every other empty field becomes null.
        $notNullDefaults = ['kitchens' => 1, 'parking_spaces' => 0, 'price' => 0];
        foreach ($data as $k => $v) {
            if ($v === '' || $v === null) {
                $data[$k] = $notNullDefaults[$k] ?? null;
            }
        }
        $data['features'] = $request->has('features') ? array_map('trim', explode(',', $request->features)) : null;
        $data['additional_rooms'] = $request->has('additional_rooms') ? $request->additional_rooms : null;
        $data['building_features'] = $request->has('building_features') ? $request->building_features : null;
        $data['community_amenities'] = $request->has('community_amenities') ? $request->community_amenities : null;
        $data['communication_features'] = $request->has('communication_features') ? $request->communication_features : null;
        $data['nearby_places'] = $request->has('nearby_places') ? $request->nearby_places : null;
        $data['utilities'] = $request->has('utilities') ? $request->utilities : null;
        $data['city_id'] = $request->city ? City::where('name', $request->city)->value('id') : null;

        // Conflict-of-interest guard: only admins may change the commission rate.
        if (! auth()->user()->isAdmin() && $request->filled('commission_rate')) {
            unset($data['commission_rate']);
        }

        $data['owner_id'] = Client::resolveOrCreate(
            $request->input('owner_id'),
            $request->input('owner_name'),
            $request->input('owner_phone'),
            'seller'
        );

        $oldStatus = $property->status;
        $property->update($data);
        $this->handleMediaUploads($request, $property);

        if ($oldStatus !== $property->status) {
            $recipients = [];
            if ($property->assignedAgent && $property->assignedAgent->user) {
                $recipients[] = $property->assignedAgent->user;
            }
            notify_company($property->company_id, PropertyStatusChanged::class, [$property, $oldStatus], $recipients);
        }

        toastr()->success('Property updated successfully.');

        return redirect()->route('properties.index');
    }

    public function destroy(Property $property)
    {
        $this->authorize('update', $property);
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
                    'is_primary' => ! $hasPrimary && $sortOrder === 1,
                    'sort_order' => $sortOrder,
                ]);
            }
        }

        if ($request->hasFile('video')) {
            $hasVideo = $property->media()->where('type', 'video')->exists();
            if (! $hasVideo) {
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

    public function exportExcel()
    {
        [$headers, $rows] = \App\Exports\PropertyExport::build();

        return \App\Services\ExcelWriter::stream('properties-'.date('Y-m-d').'.xlsx', $headers, $rows);
    }

}
