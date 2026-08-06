<?php

namespace App\Http\Controllers;

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

        $contacts = $query->paginate(15)->withQueryString();

        return view('contacts.index', compact('contacts'));
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
