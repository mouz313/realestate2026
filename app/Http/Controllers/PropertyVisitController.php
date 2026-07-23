<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class PropertyVisitController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $propertyVisits = PropertyVisit::with(['property', 'client', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->latest()->paginate(15);

        return view('property_visits.index', compact('propertyVisits'));
    }

    public function create()
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('property_visits.create', compact('properties', 'clients', 'agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        PropertyVisit::create($request->all());
        toastr()->success('Property visit added successfully.');

        return redirect()->route('property-visits.index');
    }

    public function show(PropertyVisit $propertyVisit)
    {
        $this->authorizeAgentAccess($propertyVisit);
        $propertyVisit->load(['property', 'client', 'agent']);

        return view('property_visits.show', compact('propertyVisit'));
    }

    public function edit(PropertyVisit $propertyVisit)
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('property_visits.edit', compact('propertyVisit', 'properties', 'clients', 'agents'));
    }

    public function update(Request $request, PropertyVisit $propertyVisit)
    {
        $this->authorizeAgentAccess($propertyVisit);
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        $propertyVisit->update($request->all());
        toastr()->success('Property visit updated successfully.');

        return redirect()->route('property-visits.index');
    }

    public function destroy(PropertyVisit $propertyVisit)
    {
        $this->authorizeAgentAccess($propertyVisit);
        $propertyVisit->delete();
        toastr()->success('Property visit deleted successfully.');

        return redirect()->route('property-visits.index');
    }
}
