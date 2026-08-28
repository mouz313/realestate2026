<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\CallLog;
use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class PropertyVisitController extends Controller
{
    public function index(Request $request)
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $propertyVisits = PropertyVisit::with(['property', 'client', 'agent', 'callLog'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->when($request->call_log_id, fn ($q) => $q->where('call_log_id', $request->call_log_id))
            ->latest()->paginate(15)->withQueryString();

        $leads = CallLog::whereNotIn('status', ['converted', 'lost'])
            ->orderBy('name')->get();

        return view('property_visits.index', compact('propertyVisits', 'leads'));
    }

    public function create()
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $leads = CallLog::whereNotIn('status', ['converted', 'lost'])
            ->orderBy('name')->get();

        return view('property_visits.create', compact('properties', 'clients', 'agents', 'leads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'call_log_id' => 'nullable|exists:call_logs,id',
            'agent_id' => 'nullable|exists:agents,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show,rescheduled',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($request->filled('call_log_id')) {
                $callLog = CallLog::find($request->input('call_log_id'));
                $client = $callLog?->client;

                if (! $client) {
                    $client = Client::firstOrCreate(
                        ['phone' => $callLog->phone, 'company_id' => current_company_id()],
                        [
                            'name' => $callLog->name,
                            'email' => $callLog->email,
                            'client_type' => $callLog->transaction_type === 'rent' ? 'tenant' : 'buyer',
                            'notes' => 'Created from lead #'.$callLog->id,
                        ]
                    );
                }

                $data['client_id'] = $client->id;
                $data['call_log_id'] = $callLog->id;
            }

            if (! $request->filled('agent_id') && auth()->user()->isAgent()) {
                $data['agent_id'] = auth()->user()->agent_id;
            }

            PropertyVisit::create($data);
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Something went wrong while saving the visit. Please try again.');

            return redirect()->back()->withInput();
        }

        if ($request->filled('call_log_id')) {
            toastr()->success('Property visit added successfully.');

            return redirect()->route('call-logs.show', $request->input('call_log_id'));
        }

        toastr()->success('Property visit added successfully.');

        return redirect()->route('property-visits.index');
    }

    public function show(PropertyVisit $propertyVisit)
    {
        $this->authorize('update', $propertyVisit);
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
        $this->authorize('update', $propertyVisit);
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show,rescheduled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $propertyVisit->update($request->all());
        toastr()->success('Property visit updated successfully.');

        return redirect()->route('property-visits.index');
    }

    public function destroy(PropertyVisit $propertyVisit)
    {
        $this->authorize('update', $propertyVisit);
        $propertyVisit->delete();
        toastr()->success('Property visit deleted successfully.');

        return redirect()->route('property-visits.index');
    }
}
