<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'address', 'value' => 'Islamabad, Pakistan'],
            ['key' => 'phone', 'value' => '+92 300 1234567'],
            ['key' => 'email', 'value' => 'info@example.com'],
            ['key' => 'working_hours', 'value' => 'Mon-Sat: 9AM - 7PM'],
            ['key' => 'map_lat', 'value' => '33.6844'],
            ['key' => 'map_lng', 'value' => '73.0479'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/agency'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/agency'],
            ['key' => 'social_whatsapp', 'value' => 'https://wa.me/923001234567'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@agency'],
            ['key' => 'brands', 'value' => json_encode([
                'Alpha Developers', 'Metro Construction', 'Prime Estate', 'Urban Living',
                'Capital Realty', 'Golden Homes', 'Skyline Builders', 'Sapphire Properties',
            ])],
            ['key' => 'testimonials', 'value' => json_encode([
                ['name' => 'Ahmed Khan', 'role' => 'Home Buyer', 'text' => 'Exceptional service! Found my dream home within a week. The team was incredibly professional and guided me through every step.', 'rating' => 5],
                ['name' => 'Sara Ali', 'role' => 'Property Investor', 'text' => 'I\'ve used multiple platforms but this one stands out. Verified listings, honest agents, and zero hidden fees.', 'rating' => 5],
                ['name' => 'Usman Raza', 'role' => 'Seller', 'text' => 'Sold my property in just 2 weeks at a great price. The marketing was top-notch and they handled all the paperwork.', 'rating' => 5],
                ['name' => 'Fatima Zafar', 'role' => 'Renter', 'text' => 'Found the perfect apartment in just 3 days. The search filters made it so easy to find exactly what I needed.', 'rating' => 5],
            ])],
            ['key' => 'features', 'value' => json_encode([
                ['icon' => 'ti ti-shield-check', 'title' => 'Verified Properties', 'desc' => 'Every listing is verified for authenticity and accurate details.'],
                ['icon' => 'ti ti-users', 'title' => 'Expert Agents', 'desc' => 'Experienced agents guide you through every step of the process.'],
                ['icon' => 'ti ti-file-description', 'title' => 'Legal Support', 'desc' => 'Complete documentation and legal assistance for smooth transactions.'],
                ['icon' => 'ti ti-currency-dollar', 'title' => 'Best Prices', 'desc' => 'Get competitive prices with transparent fee structures.'],
                ['icon' => 'ti ti-clock', 'title' => 'Fast Processing', 'desc' => 'Quick turnaround from property search to final possession.'],
                ['icon' => 'ti ti-headset', 'title' => '24/7 Support', 'desc' => 'Round-the-clock support for all your queries and concerns.'],
            ])],
            ['key' => 'milestones', 'value' => json_encode([
                ['year' => 2018, 'title' => 'Founded', 'desc' => 'Agency was established with a vision to transform real estate in Pakistan.'],
                ['year' => 2019, 'title' => '100 Properties Sold', 'desc' => 'Reached our first major milestone within the first year of operations.'],
                ['year' => 2021, 'title' => 'Expanded Nationwide', 'desc' => 'Opened offices in Lahore, Karachi, and Islamabad to serve more clients.'],
                ['year' => 2023, 'title' => 'Digital Transformation', 'desc' => 'Launched our online platform with virtual tours and digital documentation.'],
                ['year' => 2024, 'title' => 'Industry Leader', 'desc' => 'Recognized as one of Pakistan\'s top real estate agencies with 500+ properties sold.'],
            ])],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }
    }
}
