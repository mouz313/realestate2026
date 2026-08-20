<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Property;
use App\Models\RentalRecord;
use Illuminate\Http\Request;

class RentalRecordController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $records = RentalRecord::query()
            ->with(['property', 'tenant'])
            ->when($search, function ($query, $search) {
                $query->whereHas('property', fn ($q) => $q->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('tenant', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('rental_records.index', compact('records', 'search', 'status'));
    }

    public function create()
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('rental_records.create', compact('properties', 'clients', 'agents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'nullable|exists:clients,id',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_phone' => 'nullable|string|max:50',
            'landlord_id' => 'nullable|exists:clients,id',
            'landlord_name' => 'nullable|string|max:255',
            'landlord_phone' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'duration_months' => 'nullable|integer',
            'status' => 'required|in:active,ended',
            'notes' => 'nullable|string',
        ]);

        $data['tenant_id'] = $this->resolveClient(
            $request->input('tenant_id'),
            $request->input('tenant_name'),
            $request->input('tenant_phone'),
            'buyer'
        );
        $data['landlord_id'] = $this->resolveClient(
            $request->input('landlord_id'),
            $request->input('landlord_name'),
            $request->input('landlord_phone'),
            'seller'
        );

        $data['company_id'] = current_company_id();
        if (auth()->user()?->isAgent()) {
            $data['created_by'] = auth()->id();
        }

        $record = RentalRecord::create($data);

        $property = Property::find($data['property_id']);
        $property->status = 'rented';
        $property->save();

        toastr()->success('Rental record added successfully.');

        return redirect()->route('rental-records.index');
    }

    protected function resolveClient(?string $id, ?string $name, ?string $phone, string $type): ?int
    {
        if (! empty($id)) {
            return (int) $id;
        }

        if (! empty($name)) {
            $client = Client::create([
                'name' => $name,
                'phone' => $phone,
                'client_type' => $type,
                'company_id' => current_company_id(),
            ]);

            return $client->id;
        }

        return null;
    }

    public function show(RentalRecord $rentalRecord)
    {
        $rentalRecord->load(['property', 'tenant', 'landlord', 'creator']);

        return view('rental_records.show', compact('rentalRecord'));
    }

    public function edit(RentalRecord $rentalRecord)
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('rental_records.edit', compact('rentalRecord', 'properties', 'clients', 'agents'));
    }

    public function update(Request $request, RentalRecord $rentalRecord)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'nullable|exists:clients,id',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_phone' => 'nullable|string|max:50',
            'landlord_id' => 'nullable|exists:clients,id',
            'landlord_name' => 'nullable|string|max:255',
            'landlord_phone' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'duration_months' => 'nullable|integer',
            'status' => 'required|in:active,ended',
            'notes' => 'nullable|string',
        ]);

        $data['tenant_id'] = $this->resolveClient(
            $request->input('tenant_id'),
            $request->input('tenant_name'),
            $request->input('tenant_phone'),
            'buyer'
        );
        $data['landlord_id'] = $this->resolveClient(
            $request->input('landlord_id'),
            $request->input('landlord_name'),
            $request->input('landlord_phone'),
            'seller'
        );

        $rentalRecord->update($data);

        $property = Property::find($data['property_id']);
        $property->status = $rentalRecord->status === 'ended' ? 'available' : 'rented';
        $property->save();

        toastr()->success('Rental record updated successfully.');

        return redirect()->route('rental-records.index');
    }

    public function destroy(RentalRecord $rentalRecord)
    {
        $property = $rentalRecord->property;
        $rentalRecord->delete();

        if ($property) {
            $property->status = 'available';
            $property->save();
        }

        toastr()->success('Rental record deleted successfully.');

        return redirect()->route('rental-records.index');
    }
}
