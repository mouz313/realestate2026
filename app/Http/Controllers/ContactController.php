<?php

namespace App\Http\Controllers;

use App\Helpers\Status;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with('property')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('subject', 'like', "%{$s}%")
                    ->orWhere('property_title', 'like', "%{$s}%");
            });
        }

        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        $contacts = $query->paginate(15)->withQueryString();

        $leadSources = Status::leadSources();
        $propertyTypes = Status::enquiryPropertyTypes();
        $purposes = Status::purposes();

        return view('contacts.index', compact('contacts', 'leadSources', 'propertyTypes', 'purposes'));
    }

    public function create()
    {
        $leadSources = Status::leadSources();
        $propertyTypes = Status::enquiryPropertyTypes();
        $purposes = Status::purposes();

        return view('contacts.create', compact('leadSources', 'propertyTypes', 'purposes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'property_type' => 'required|string|in:house,flat,farmhouse,plot,building',
            'purpose' => 'required|string|in:buy,rent',
            'city' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:120',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'lead_source' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:2000',
        ]);

        $data['company_id'] = current_company_id();
        $data['lead_source'] = $data['lead_source'] ?? 'walk_in';
        $data['read_at'] = null;

        $contact = Contact::create($data);

        Client::firstOrCreate(
            ['phone' => $data['phone'], 'company_id' => $data['company_id']],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'client_type' => $data['purpose'] === 'rent' ? 'tenant' : 'buyer',
                'notes' => 'Created from walk-in enquiry #'.$contact->id,
            ]
        );

        toastr()->success('Enquiry saved and client created.');

        return redirect()->route('contacts.index');
    }

    public function edit(Contact $contact)
    {
        $leadSources = Status::leadSources();
        $propertyTypes = Status::enquiryPropertyTypes();
        $purposes = Status::purposes();

        return view('contacts.edit', compact('contact', 'leadSources', 'propertyTypes', 'purposes'));
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'property_type' => 'required|string|in:house,flat,farmhouse,plot,building',
            'purpose' => 'required|string|in:buy,rent',
            'city' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:120',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'lead_source' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:2000',
        ]);

        $contact->update($data);

        toastr()->success('Enquiry updated.');

        return redirect()->route('contacts.show', $contact);
    }

    public function show(Contact $contact)
    {
        $contact->load('property');

        if (! $contact->read_at) {
            $contact->update(['read_at' => now()]);
        }

        return view('contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        toastr()->success('Enquiry deleted successfully.');

        return redirect()->route('contacts.index');
    }
}
