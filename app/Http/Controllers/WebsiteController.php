<?php

namespace App\Http\Controllers;

use App\Helpers\Status;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Property;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function home()
    {
        $featuredProperties = Property::with(['primaryMedia', 'media'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'properties' => Property::count(),
            'sold' => Property::where('status', 'sold')->count(),
            'agents' => Agent::count(),
            'clients' => Client::count(),
        ];

        $cities = Property::where('status', 'available')
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->take(8)
            ->pluck('city');

        $typeCounts = Property::where('status', 'available')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $settings = Setting::pluck('value', 'key');
        $sliderImages = json_decode($settings['slider_images'] ?? '[]', true);
        $testimonials = json_decode($settings['testimonials'] ?? '[]', true);
        $features = json_decode($settings['features'] ?? '[]', true);
        $brands = json_decode($settings['brands'] ?? '[]', true);

        $reviews = Review::approved()
            ->with('property')
            ->latest()
            ->take(10)
            ->get();
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

        return view('website.home', compact(
            'featuredProperties', 'stats', 'cities',
            'typeCounts', 'sliderImages', 'testimonials', 'features',
            'brands', 'social', 'contactInfo', 'reviews'
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

        $stats = [
            'properties' => Property::count(),
            'sold' => $totalSold,
            'agents' => Agent::count(),
            'clients' => Client::count(),
        ];

        return view('website.about', compact('team', 'milestones', 'social', 'contactInfo', 'totalSold', 'stats'));
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

        return view('website.contact', compact('settings', 'social', 'contactInfo'));
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

        $validated['lead_source'] = $this->resolveLeadSource($request);

        Contact::create($validated);

        \Mail::raw(
            "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nSubject: {$request->subject}\n\nMessage:\n{$request->message}",
            fn ($msg) => $msg->to(config('app.admin_email', 'admin@example.com'))
                ->subject('New Contact Inquiry - '.config('app.name'))
        );

        toastr()->success('Thank you! We will get back to you soon.');

        return back();
    }

    protected function resolveLeadSource(Request $request): string
    {
        $allowed = array_keys(Status::leadSources());
        $source = $request->input('source');

        return in_array($source, $allowed, true) ? $source : 'website';
    }

    public function submitEnquiry(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:5000',
        ]);

        $validated['property_id'] = $property->id;
        $validated['property_title'] = $property->title;
        $validated['subject'] = 'Property Enquiry: '.$property->property_code;
        $validated['lead_source'] = $this->resolveLeadSource($request);

        Contact::create($validated);

        \Mail::raw(
            "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\n\nProperty: {$property->title} ({$property->property_code})\nURL: ".route('website.property', $property)."\n\nMessage:\n{$request->message}",
            fn ($msg) => $msg->to(config('app.admin_email', 'admin@example.com'))
                ->subject('New Property Enquiry - '.config('app.name'))
        );

        toastr()->success('Thank you! Our agent will contact you about this property soon.');

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

        return view('website.privacy', compact('social', 'contactInfo'));
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

        return view('website.terms', compact('social', 'contactInfo'));
    }

    public function sitemap()
    {
        $urls = [
            ['loc' => url('/'), 'freq' => 'daily', 'priority' => '1.0'],
            ['loc' => url('/listings'), 'freq' => 'daily', 'priority' => '0.9'],
            ['loc' => url('/about'), 'freq' => 'monthly', 'priority' => '0.7'],
            ['loc' => url('/blog'), 'freq' => 'daily', 'priority' => '0.6'],
            ['loc' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.6'],
            ['loc' => url('/privacy'), 'freq' => 'yearly', 'priority' => '0.3'],
            ['loc' => url('/terms'), 'freq' => 'yearly', 'priority' => '0.3'],
        ];

        $posts = Post::published()->get(['slug']);
        foreach ($posts as $post) {
            $urls[] = ['loc' => route('website.blog.show', $post), 'freq' => 'weekly', 'priority' => '0.5'];
        }

        $properties = Property::where('status', 'available')->get(['id']);
        foreach ($properties as $property) {
            $urls[] = ['loc' => route('website.property', $property), 'freq' => 'weekly', 'priority' => '0.8'];
        }

        return response()->view('website.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }

    public function properties(Request $request)
    {
        $query = Property::with(['primaryMedia', 'owner'])->where('status', 'available');

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
        $cities = Property::where('status', 'available')
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

        return view('website.listings', compact(
            'properties', 'cities', 'types',
            'social', 'contactInfo'
        ));
    }

    public function property(Property $property)
    {
        if ($property->status !== 'available') {
            abort(404);
        }
        $property->increment('views_count');
        $property->load(['owner', 'assignedAgent', 'media', 'documents', 'approvedReviews']);
        $reviewAvg = $property->approvedReviews->avg('rating');
        $reviewCount = $property->approvedReviews->count();
        $related = Property::with('primaryMedia')
            ->where('id', '!=', $property->id)
            ->where(function ($q) use ($property) {
                $q->where('city', $property->city)
                    ->orWhere('type', $property->type);
            })
            ->where('status', 'available')
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
            'wa_phone' => preg_replace('/[^0-9]/', '', $settings['phone'] ?? '+92 300 1234567'),
            'email' => $settings['email'] ?? 'info@example.com',
            'hours' => $settings['working_hours'] ?? 'Mon-Sat: 9AM - 7PM',
        ];

        return view('website.property-show', compact('property', 'related', 'social', 'contactInfo', 'reviewAvg', 'reviewCount'));
    }

    public function blog()
    {
        $posts = Post::published()->paginate(9);

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

        return view('website.blog', compact('posts', 'social', 'contactInfo'));
    }

    public function post(Post $post)
    {
        if (! $post->is_published || ! $post->published_at || $post->published_at->gt(now())) {
            abort(404);
        }

        $related = Post::published()->where('id', '!=', $post->id)->take(3)->get();

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

        return view('website.blog-show', compact('post', 'related', 'social', 'contactInfo'));
    }

    public function compare()
    {
        $ids = session()->get('compare', []);

        $properties = Property::with(['primaryMedia', 'owner', 'assignedAgent'])
            ->whereIn('id', $ids)
            ->where('status', 'available')
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

        return view('website.compare', compact('properties', 'social', 'contactInfo'));
    }

    public function compareAdd(Property $property)
    {
        $compare = session()->get('compare', []);

        if (! in_array($property->id, $compare, true)) {
            $compare[] = $property->id;
            session(['compare' => array_slice($compare, -4)]);
            toastr()->success('Added to compare.');
        }

        return redirect()->route('website.compare');
    }

    public function compareRemove(Property $property)
    {
        $compare = array_values(array_filter(session()->get('compare', []), fn ($id) => $id != $property->id));
        session(['compare' => $compare]);

        return redirect()->route('website.compare');
    }

    public function storeReview(Request $request, Property $property)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $data['property_id'] = $property->id;
        $data['approved'] = false;

        Review::create($data);

        toastr()->success('Review submitted. It will appear after moderation.');

        return back();
    }
}
