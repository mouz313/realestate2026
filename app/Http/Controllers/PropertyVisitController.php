<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class PropertyVisitController extends Controller
{
    public function index(Request $request)
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $propertyVisits = PropertyVisit::with(['property', 'client', 'agent', 'contact'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->when($request->contact_id, fn ($q) => $q->where('contact_id', $request->contact_id))
            ->latest()->paginate(15)->withQueryString();

        $enquiries = Contact::whereIn('status', [Contact::STATUS_OPEN, Contact::STATUS_PENDING])
            ->orderBy('name')->get();

        return view('property_visits.index', compact('propertyVisits', 'enquiries'));
    }

    public function create()
    {
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $enquiries = Contact::whereIn('status', [Contact::STATUS_OPEN, Contact::STATUS_PENDING])
            ->orderBy('name')->get();

        return view('property_visits.create', compact('properties', 'clients', 'agents', 'enquiries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'agent_id' => 'nullable|exists:agents,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show,rescheduled',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($request->filled('contact_id')) {
                $contact = Contact::find($request->input('contact_id'));
                $client = $contact?->client();

                if (! $client) {
                    $client = Client::firstOrCreate(
                        ['phone' => $contact->phone, 'company_id' => current_company_id()],
                        [
                            'name' => $contact->name,
                            'email' => $contact->email,
                            'client_type' => $contact->purpose === 'rent' ? 'tenant' : 'buyer',
                            'notes' => 'Created from enquiry #'.$contact->id,
                        ]
                    );
                }

                $data['client_id'] = $client->id;
                $data['contact_id'] = $contact->id;
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

        if ($request->filled('contact_id')) {
            toastr()->success('Property visit added successfully.');

            return redirect()->route('contacts.show', $request->input('contact_id'));
        }

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
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show,rescheduled',
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
