<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(fn ($req, $next) => Gate::authorize('admin') ? $next($req) : abort(403));
    }

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // Business
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:500',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_label' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:10',
            // Branding
            'brand_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'brand_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,webp|max:1024',
            // Email
            'mail_driver' => 'nullable|string|max:50',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|numeric|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:50',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            // Payment
            'payment_terms' => 'nullable|numeric|min:0|max:365',
            'payment_methods' => 'nullable|string|max:500',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'bank_iban' => 'nullable|string|max:255',
            'bank_swift' => 'nullable|string|max:255',
            // Real Estate
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'default_agent_commission_share' => 'nullable|numeric|min:0|max:100',
            'token_percentage' => 'nullable|numeric|min:0|max:100',
            'listing_expiry_days' => 'nullable|integer|min:1|max:365',
            'default_city' => 'nullable|string|max:100',
            'property_viewing_duration' => 'nullable|integer|min:5|max:240',
            'rental_commission_months' => 'nullable|numeric|min:0|max:12',
            'enable_cnic_verification' => 'nullable|boolean',
            // SMS
            'sms_provider' => 'nullable|string|max:50',
            'sms_username' => 'nullable|string|max:255',
            'sms_password' => 'nullable|string|max:255',
            'sms_sender' => 'nullable|string|max:50',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            if ($key === 'brand_logo' && $request->hasFile('brand_logo')) {
                $old = Setting::where('key', 'brand_logo')->value('value');
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file('brand_logo')->store('brand', 'public');
                Setting::updateOrCreate(['key' => 'brand_logo'], ['value' => $path]);

                continue;
            }

            if ($key === 'brand_favicon' && $request->hasFile('brand_favicon')) {
                $old = Setting::where('key', 'brand_favicon')->value('value');
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file('brand_favicon')->store('brand', 'public');
                Setting::updateOrCreate(['key' => 'brand_favicon'], ['value' => $path]);

                continue;
            }

            if ($key === 'slider_images' && $request->hasFile('slider_images')) {
                $existing = json_decode(Setting::where('key', 'slider_images')->value('value') ?? '[]', true);
                $uploads = $request->file('slider_images');
                $titles = $request->input('slider_titles', []);
                $subtitles = $request->input('slider_subtitles', []);

                foreach ($uploads as $i => $file) {
                    $path = crop_and_save($file, 'sliders');
                    $existing[] = [
                        'image' => $path,
                        'title' => $titles[$i] ?? '',
                        'subtitle' => $subtitles[$i] ?? '',
                    ];
                }

                Setting::updateOrCreate(['key' => 'slider_images'], ['value' => json_encode($existing)]);

                continue;
            }

            if (str_starts_with($key, 'delete_slider_')) {
                $idx = (int) str_replace('delete_slider_', '', $key);
                $existing = json_decode(Setting::where('key', 'slider_images')->value('value') ?? '[]', true);
                if (isset($existing[$idx])) {
                    if (Storage::disk('public')->exists($existing[$idx]['image'])) {
                        Storage::disk('public')->delete($existing[$idx]['image']);
                    }
                    array_splice($existing, $idx, 1);
                }
                Setting::updateOrCreate(['key' => 'slider_images'], ['value' => json_encode($existing)]);

                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        toastr()->success('Settings saved successfully.');

        return back();
    }
}
