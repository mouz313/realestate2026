<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $client = $this->getClient();
        $visits = PropertyVisit::with(['property', 'agent'])
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(12);

        return view('portal.visits.index', compact('visits'));
    }

    public function create(Request $request)
    {
        $client = $this->getClient();
        $properties = Property::where('status', 'available')->orderBy('title')->get();
        $selectedProperty = $request->property_id ? Property::find($request->property_id) : null;

        return view('portal.visits.create', compact('properties', 'selectedProperty'));
    }

    public function store(Request $request)
    {
        $client = $this->getClient();
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'scheduled_date' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $property = Property::findOrFail($request->property_id);

        PropertyVisit::create([
            'property_id' => $request->property_id,
            'client_id' => $client->id,
            'agent_id' => $property->assigned_agent_id,
            'scheduled_date' => $request->scheduled_date,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->route('portal.visits')->with('success', 'Visit requested successfully.');
    }

    private function getClient()
    {
        $client = Client::find(session('client_id'));
        abort_if(! $client, 401);

        return $client;
    }
}
