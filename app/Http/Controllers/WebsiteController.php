<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Property;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function home()
    {
        $featuredProperties = Property::with(['primaryMedia', 'media'])
            ->whereIn('status', ['available', 'pending'])
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'properties' => Property::count(),
            'sold' => Property::where('status', 'sold')->count(),
            'agents' => Agent::count(),
            'clients' => Client::count(),
        ];

        $cities = Property::whereIn('status', ['available', 'pending'])
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->take(8)
            ->pluck('city');

        $typeCounts = Property::whereIn('status', ['available', 'pending'])
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $settings = Setting::pluck('value', 'key');
        $sliderImages = json_decode($settings['slider_images'] ?? '[]', true);
        $testimonials = json_decode($settings['testimonials'] ?? '[]', true);
        $features = json_decode($settings['features'] ?? '[]', true);
        $brands = json_decode($settings['brands'] ?? '[]', true);
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('public.home', compact(
            'featuredProperties', 'stats', 'cities',
            'typeCounts', 'sliderImages', 'testimonials', 'features',
            'brands', 'social', 'contactInfo'
        ));
    }

    public function about()
    {
        $team = Agent::get();
        if ($team->isEmpty()) {
            $team = collect([
                (object) ['name' => 'Ahmed Khan', 'role' => 'CEO & Founder', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object) ['name' => 'Sara Ali', 'role' => 'Head of Operations', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object) ['name' => 'Usman Malik', 'role' => 'Senior Agent', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
                (object) ['name' => 'Fatima Ahmed', 'role' => 'Legal Advisor', 'photo' => null, 'whatsapp' => null, 'email' => null, 'facebook' => null, 'linkedin' => null, 'instagram' => null, 'experience_years' => null, 'languages' => null, 'bio' => null, 'specializations' => null],
            ]);
        }
        $settings = Setting::pluck('value', 'key');
        $milestones = json_decode($settings['milestones'] ?? '[]', true);
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        $totalSold = Property::where('status', 'sold')->count();

        return view('public.about', compact('team', 'milestones', 'social', 'contactInfo', 'totalSold'));
    }

    public function contact()
    {
        $settings = Setting::pluck('value', 'key');
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
            'map_lat' => $settings['map_lat'] ?? '33.6844',
            'map_lng' => $settings['map_lng'] ?? '73.0479',
        ];

        return view('public.contact', compact('settings', 'social', 'contactInfo'));
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
                ->subject('New Contact Inquiry - '.config('app.name'))
        );

        toastr()->success('Thank you! We will get back to you soon.');

        return back();
    }

    public function privacy()
    {
        $settings = Setting::pluck('value', 'key');
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('public.privacy', compact('social', 'contactInfo'));
    }

    public function terms()
    {
        $settings = Setting::pluck('value', 'key');
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('public.terms', compact('social', 'contactInfo'));
    }

    public function sitemap()
    {
        $urls = [
            ['loc' => url('/'), 'freq' => 'daily', 'priority' => '1.0'],
            ['loc' => url('/listings'), 'freq' => 'daily', 'priority' => '0.9'],
            ['loc' => url('/about'), 'freq' => 'monthly', 'priority' => '0.7'],
            ['loc' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.6'],
            ['loc' => url('/privacy'), 'freq' => 'yearly', 'priority' => '0.3'],
            ['loc' => url('/terms'), 'freq' => 'yearly', 'priority' => '0.3'],
        ];

        return response()->view('public.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
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

        $settings = Setting::pluck('value', 'key');
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('public.properties', compact(
            'properties', 'cities', 'types',
            'social', 'contactInfo'
        ));
    }

    public function property(Property $property)
    {
        if (! in_array($property->status, ['available', 'pending'])) {
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

        $settings = Setting::pluck('value', 'key');
        $social = [
            'facebook' => $settings['social_facebook'] ?? '#',
            'instagram' => $settings['social_instagram'] ?? '#',
            'whatsapp' => $settings['social_whatsapp'] ?? '#',
            'youtube' => $settings['social_youtube'] ?? '#',
        ];
        $contactInfo = [
            'address' => $settings['address'] ?? 'Islamabad, Pakistan',
            'phone' => $settings['phone'] ?? '+92 300 1234567',
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('public.property', compact('property', 'related', 'social', 'contactInfo'));
    }
}
