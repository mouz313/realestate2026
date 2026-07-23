<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        $featuredProperties = Property::with(['primaryMedia', 'owner'])
            ->whereIn('status', ['available', 'pending'])
            ->latest()
            ->take(6)
            ->get();
        $stats = [
            'properties' => Property::count(),
            'sold' => Property::where('status', 'sold')->count(),
            'agents' => \App\Models\Agent::count(),
            'clients' => \App\Models\Client::count(),
        ];
        $cities = Property::whereIn('status', ['available', 'pending'])
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->take(8)
            ->pluck('city');
        return view('public.home', compact('featuredProperties', 'stats', 'cities'));
    }

    public function about()
    {
        $team = \App\Models\Agent::get();
        if ($team->isEmpty()) {
            $team = collect([
                (object)['name' => 'Ahmed Khan', 'role' => 'CEO & Founder', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object)['name' => 'Sara Ali', 'role' => 'Head of Operations', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object)['name' => 'Usman Malik', 'role' => 'Senior Agent', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object)['name' => 'Fatima Ahmed', 'role' => 'Legal Advisor', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
            ]);
        }
        return view('public.about', compact('team'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        Contact::create($validated);

        \Mail::raw(
            "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nSubject: {$request->subject}\n\nMessage:\n{$request->message}",
            fn ($msg) => $msg->to(config('app.admin_email', 'admin@example.com'))
                ->subject('New Contact Inquiry - ' . config('app.name'))
        );

        toastr()->success('Thank you! We will get back to you soon.');
        return back();
    }

    public function properties(Request $request)
    {
        $query = Property::with(['primaryMedia', 'owner'])->whereIn('status', ['available', 'pending']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('location_address', 'like', "%{$s}%")
                  ->orWhere('sector_town', 'like', "%{$s}%");
            });
        }

        $properties = $query->latest()->paginate(12)->withQueryString();
        $cities = Property::whereIn('status', ['available', 'pending'])
            ->whereNotNull('city')
            ->distinct('city')
            ->pluck('city');
        $types = ['house', 'flat', 'plot', 'commercial', 'farmhouse', 'penthouse'];

        return view('public.properties', compact('properties', 'cities', 'types'));
    }

    public function property(Property $property)
    {
        if (!in_array($property->status, ['available', 'pending'])) {
            abort(404);
        }
        $property->load(['owner', 'assignedAgent', 'media', 'documents']);
        $related = Property::with('primaryMedia')
            ->where('id', '!=', $property->id)
            ->where(function ($q) use ($property) {
                $q->where('city', $property->city)
                  ->orWhere('type', $property->type);
            })
            ->whereIn('status', ['available', 'pending'])
            ->take(4)
            ->get();
        return view('public.property', compact('property', 'related'));
    }
}
