<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USERS ───────────────────────────────────────────
        $users = [
            ['name' => 'Alam Gir', 'email' => 'alam@gmail.com'],
            ['name' => 'Rana Tanveer', 'email' => 'rana@gmail.com'],
        ];
        $createdUsers = [];
        foreach ($users as $u) {
            $createdUsers[] = User::firstOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => Hash::make('password'), 'role' => 'agent']
            );
        }
        $this->command->info('Users created: alam@gmail.com / password, rana@gmail.com / password');

        // ─── AGENTS ──────────────────────────────────────────
        $agentsData = [
            ['name' => 'Ahmed Khan', 'role' => 'CEO & Founder', 'phone' => '03001234567', 'whatsapp' => '923001234567', 'email' => 'ahmed@agency.com', 'cnic' => '1234512345671', 'commission_rate' => 3.50, 'experience_years' => 12, 'languages' => 'Urdu, English, Punjabi', 'bio' => 'Founder of the agency with over a decade of experience in Pakistan\'s real estate market. Specializes in luxury residential and commercial properties.', 'specializations' => json_encode(['Luxury', 'Residential', 'Commercial']), 'facebook' => 'https://facebook.com/ahmedkhan', 'linkedin' => 'https://linkedin.com/in/ahmedkhan'],
            ['name' => 'Sara Ali', 'role' => 'Head of Operations', 'phone' => '03002345678', 'whatsapp' => '923002345678', 'email' => 'sara@agency.com', 'cnic' => '1234512345672', 'commission_rate' => 3.00, 'experience_years' => 8, 'languages' => 'Urdu, English, Sindhi', 'bio' => 'Operations head ensuring smooth property transactions. Expert in property documentation and legal compliance.', 'specializations' => json_encode(['Residential', 'Commercial']), 'linkedin' => 'https://linkedin.com/in/saraali'],
            ['name' => 'Usman Malik', 'role' => 'Senior Agent', 'phone' => '03003456789', 'whatsapp' => '923003456789', 'email' => 'usman@agency.com', 'cnic' => '1234512345673', 'commission_rate' => 2.75, 'experience_years' => 6, 'languages' => 'Urdu, English, Pashto', 'bio' => 'Top-performing senior agent with expertise in residential plots and farmhouses across Islamabad and Lahore.', 'specializations' => json_encode(['Land/Plots', 'Agricultural', 'Residential']), 'facebook' => 'https://facebook.com/usmanmalik', 'instagram' => 'https://instagram.com/usmanmalik'],
            ['name' => 'Fatima Ahmed', 'role' => 'Legal Advisor', 'phone' => '03004567890', 'whatsapp' => '923004567890', 'email' => 'fatima@agency.com', 'cnic' => '1234512345674', 'commission_rate' => 2.50, 'experience_years' => 10, 'languages' => 'Urdu, English', 'bio' => 'Legal expert specializing in property law, title verification, and contract drafting for all types of real estate transactions.', 'specializations' => json_encode(['Residential', 'Commercial', 'Industrial']), 'linkedin' => 'https://linkedin.com/in/fatimaahmed'],
            ['name' => 'Zaid Rahim', 'role' => 'Property Consultant', 'phone' => '03005678901', 'whatsapp' => '923005678901', 'email' => 'zaid@agency.com', 'cnic' => '1234512345675', 'commission_rate' => 2.50, 'experience_years' => 4, 'languages' => 'Urdu, English, Punjabi', 'bio' => 'Young and energetic consultant specializing in rental properties and first-time home buyers across Karachi.', 'specializations' => json_encode(['Rental', 'Residential']), 'instagram' => 'https://instagram.com/zaidrahim'],
            ['name' => 'Ayesha Noor', 'role' => 'Marketing Manager', 'phone' => '03006789012', 'whatsapp' => '923006789012', 'email' => 'ayesha@agency.com', 'cnic' => '1234512345676', 'commission_rate' => 2.00, 'experience_years' => 5, 'languages' => 'Urdu, English, Kashmiri', 'bio' => 'Marketing professional handling property listings, digital campaigns, and client engagement strategies.', 'specializations' => json_encode(['Luxury', 'Residential']), 'facebook' => 'https://facebook.com/ayeshanoor', 'instagram' => 'https://instagram.com/ayeshanoor'],
        ];
        foreach ($agentsData as $i => $a) {
            Agent::firstOrCreate(
                ['email' => $a['email']],
                [
                    'name' => $a['name'],
                    'role' => $a['role'],
                    'phone' => $a['phone'],
                    'whatsapp' => $a['whatsapp'],
                    'cnic' => $a['cnic'],
                    'commission_rate' => $a['commission_rate'],
                    'type' => 'in_house',
                    'status' => 'active',
                    'join_date' => Carbon::now()->subYears(rand(1, 5))->subMonths(rand(0, 11)),
                    'address' => fake()->address(),
                    'license_number' => 'LIC-' . str_pad((string)rand(1000, 9999), 6, '0', STR_PAD_LEFT),
                    'experience_years' => $a['experience_years'],
                    'languages' => $a['languages'],
                    'bio' => $a['bio'],
                    'specializations' => $a['specializations'],
                    'facebook' => $a['facebook'] ?? null,
                    'linkedin' => $a['linkedin'] ?? null,
                    'instagram' => $a['instagram'] ?? null,
                ]
            );
        }
        // Link first 2 agent records to the 2 agent users
        $agentRecords = Agent::take(2)->get();
        foreach ($createdUsers as $i => $user) {
            if (isset($agentRecords[$i])) {
                $user->update(['agent_id' => $agentRecords[$i]->id]);
                $agentRecords[$i]->update(['user_id' => $user->id]);
            }
        }
        $this->command->info('6 Agents created. Alam → Agent 1, Rana → Agent 2');

        // ─── CLIENTS ─────────────────────────────────────────
        $clientsData = [
            ['name' => 'Muhammad Ali', 'company' => 'Ali Enterprises', 'email' => 'ali@example.com', 'phone' => '03001112221', 'address' => 'House 12, Street 5, F-7/4, Islamabad'],
            ['name' => 'Bilal Hussain', 'company' => 'BH Trading', 'email' => 'bilal@example.com', 'phone' => '03001112222', 'address' => '45-D, Main Boulevard, Gulberg, Lahore'],
            ['name' => 'Sana Tariq', 'company' => null, 'email' => 'sana@example.com', 'phone' => '03001112223', 'address' => 'Flat 3B, Ocean Tower, Clifton, Karachi'],
            ['name' => 'Kamran Shah', 'company' => 'Shah Group', 'email' => 'kamran@example.com', 'phone' => '03001112224', 'address' => '19-B, Phase 5, DHA, Karachi'],
            ['name' => 'Hina Aslam', 'company' => null, 'email' => 'hina@example.com', 'phone' => '03001112225', 'address' => '8-A, College Road, Satellite Town, Rawalpindi'],
            ['name' => 'Omar Farooq', 'company' => 'Farooq Sons', 'email' => 'omar@example.com', 'phone' => '03001112226', 'address' => 'Plot 7, Industrial Area, Faisalabad'],
            ['name' => 'Zainab Iqbal', 'company' => null, 'email' => 'zainab@example.com', 'phone' => '03001112227', 'address' => '56-B, Model Town, Multan'],
            ['name' => 'Rizwan Ahmed', 'company' => 'Rizwan Realtors', 'email' => 'rizwan@example.com', 'phone' => '03001112228', 'address' => 'Office 4, Plaza 22, Blue Area, Islamabad'],
            ['name' => 'Mahnoor Sheikh', 'company' => null, 'email' => 'mahnoor@example.com', 'phone' => '03001112229', 'address' => 'House 3, Street 12, G-10/2, Islamabad'],
            ['name' => 'Tariq Mehmood', 'company' => 'TM Industries', 'email' => 'tariq@example.com', 'phone' => '03001112230', 'address' => '22-A, Shadman Market, Lahore'],
        ];
        foreach ($clientsData as $c) {
            Client::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'company' => $c['company'],
                    'phone' => $c['phone'],
                    'address' => $c['address'],
                    'client_type' => 'buyer',
                    'cnic' => '42000' . str_pad((string)rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'cnic_verified' => (bool)rand(0, 1),
                    'notes' => rand(0, 1) ? 'Preferred client' : null,
                ]
            );
        }
        $this->command->info('10 Clients created.');

        // ─── PROPERTIES ──────────────────────────────────────
        $agentIds = Agent::pluck('id')->toArray();
        $ownerIds = Client::pluck('id')->toArray();
        $propertiesData = [
            ['title' => 'Luxury 5-Bed Villa with Pool', 'type' => 'house', 'transaction_type' => 'sale', 'price' => 45000000, 'city' => 'Islamabad', 'sector_town' => 'F-7/4', 'bedrooms' => 5, 'bathrooms' => 6, 'plot_size' => 1200, 'plot_size_unit' => 'sqft', 'covered_area' => 6500, 'furnished' => true, 'description' => 'Stunning luxury villa in the heart of Islamabad with modern architecture, private pool, landscaped garden, and smart home features.' , 'features' => ['Central AC', 'Heating', 'Generator Backup', 'Smart Home System', 'CCTV'], 'community_amenities' => ['Gym', 'Park', 'Security', 'Mosque'], 'nearby_places' => ['Super Market', 'School', 'Hospital'], 'possession_status' => 'ready'],
            ['title' => 'Modern 3-Bed Apartment with City View', 'type' => 'flat', 'transaction_type' => 'sale', 'price' => 18500000, 'city' => 'Lahore', 'sector_town' => 'Gulberg', 'bedrooms' => 3, 'bathrooms' => 3, 'plot_size' => null, 'plot_size_unit' => null, 'covered_area' => 1800, 'furnished' => false, 'description' => 'Premium apartment on 12th floor with panoramic city views, modern kitchen, and high-end finishes in the heart of Lahore.' , 'features' => ['Central AC', 'Elevator', 'Security', 'Parking'], 'community_amenities' => ['Gym', 'Swimming Pool', 'Community Hall'], 'nearby_places' => ['MM Alam Road', 'Shopping Mall', 'Restaurants'], 'possession_status' => 'ready'],
            ['title' => 'Commercial Plaza - Prime Location', 'type' => 'commercial', 'transaction_type' => 'sale', 'price' => 120000000, 'city' => 'Karachi', 'sector_town' => 'Clifton', 'bedrooms' => null, 'bathrooms' => 4, 'plot_size' => 400, 'plot_size_unit' => 'sqft', 'covered_area' => 12000, 'furnished' => false, 'description' => 'Prime commercial plaza on main Clifton road. Ground + 3 floors, ideal for showrooms, offices, or retail.' , 'features' => ['Central AC', 'Elevator', 'Generator', 'Parking'], 'community_amenities' => ['Security', 'Maintenance'], 'nearby_places' => ['Dolmen Mall', 'Beach', 'Hotels'], 'possession_status' => 'ready'],
            ['title' => 'Residential Plot in DHA Phase 8', 'type' => 'plot', 'transaction_type' => 'sale', 'price' => 8500000, 'city' => 'Karachi', 'sector_town' => 'DHA Phase 8', 'bedrooms' => null, 'bathrooms' => null, 'plot_size' => 200, 'plot_size_unit' => 'sqft', 'covered_area' => null, 'furnished' => false, 'description' => 'A prime residential plot in the most sought-after DHA Phase 8. Perfect for building your dream home.' , 'features' => null, 'community_amenities' => ['Park', 'Security', 'Mosque'], 'nearby_places' => ['School', 'Hospital', 'Market'], 'possession_status' => 'ready'],
            ['title' => 'Farmhouse with Orchard', 'type' => 'farmhouse', 'transaction_type' => 'sale', 'price' => 65000000, 'city' => 'Lahore', 'sector_town' => 'Raiwind Road', 'bedrooms' => 6, 'bathrooms' => 5, 'plot_size' => 8, 'plot_size_unit' => 'kanal', 'covered_area' => 8000, 'furnished' => true, 'description' => 'Beautiful farmhouse with fruit orchard, swimming pool, and spacious lawns. Perfect weekend getaway near Lahore.' , 'features' => ['Central AC', 'Heating', 'Generator', 'Swimming Pool', 'CCTV'], 'community_amenities' => null, 'nearby_places' => ['Raiwind Road', 'Lahore Ring Road'], 'possession_status' => 'ready'],
            ['title' => '3-Bed Family House in Bahria Town', 'type' => 'house', 'transaction_type' => 'sale', 'price' => 22000000, 'city' => 'Rawalpindi', 'sector_town' => 'Bahria Town', 'bedrooms' => 3, 'bathrooms' => 3, 'plot_size' => 500, 'plot_size_unit' => 'sqft', 'covered_area' => 2800, 'furnished' => false, 'description' => 'Spacious family house in the secure environment of Bahria Town. Modern design with all amenities nearby.' , 'features' => ['Central AC', 'Generator', 'Security'], 'community_amenities' => ['Park', 'Gym', 'Swimming Pool', 'Security'], 'nearby_places' => ['School', 'Hospital', 'Market'], 'possession_status' => 'ready'],
            ['title' => 'Luxury Penthouse - 4-Bed', 'type' => 'flat', 'transaction_type' => 'sale', 'price' => 55000000, 'city' => 'Islamabad', 'sector_town' => 'E-7', 'bedrooms' => 4, 'bathrooms' => 4, 'plot_size' => null, 'plot_size_unit' => null, 'covered_area' => 3500, 'furnished' => true, 'description' => 'Exclusive penthouse in the diplomatic enclave with stunning Margalla Hills views, private terrace, and premium fittings.' , 'features' => ['Central AC', 'Underfloor Heating', 'Smart Home', 'CCTV', 'Private Terrace'], 'community_amenities' => ['Gym', 'Security', 'Parking'], 'nearby_places' => ['Diplomatic Enclave', 'Centaurus Mall', 'Marriott Hotel'], 'possession_status' => 'ready'],
            ['title' => 'Commercial Shop - Saddar Bazar', 'type' => 'commercial', 'transaction_type' => 'sale', 'price' => 35000000, 'city' => 'Rawalpindi', 'sector_town' => 'Saddar', 'bedrooms' => null, 'bathrooms' => 1, 'plot_size' => 80, 'plot_size_unit' => 'sqft', 'covered_area' => 720, 'furnished' => false, 'description' => 'High-foot-traffic commercial shop in Saddar Bazar. Currently rented, generating excellent monthly income.' , 'features' => ['Air Conditioning', 'Store Room'], 'community_amenities' => null, 'nearby_places' => ['Saddar Bazar', 'Metro Station'], 'possession_status' => 'ready'],
            ['title' => 'Agricultural Land 25 Acres', 'type' => 'farmhouse', 'transaction_type' => 'sale', 'price' => 15000000, 'city' => 'Multan', 'sector_town' => 'Basti Malook', 'bedrooms' => null, 'bathrooms' => null, 'plot_size' => 25, 'plot_size_unit' => 'acre', 'covered_area' => null, 'furnished' => false, 'description' => 'Productive agricultural land with tube well irrigation. Ideal for farming or future development near Multan.' , 'features' => ['Tube Well', 'Electricity'], 'community_amenities' => null, 'nearby_places' => ['Multan City', 'Motorway M4'], 'possession_status' => 'ready'],
            ['title' => 'Modern Office in Blue Area', 'type' => 'commercial', 'transaction_type' => 'rent', 'price' => 150000, 'city' => 'Islamabad', 'sector_town' => 'Blue Area', 'bedrooms' => null, 'bathrooms' => 2, 'plot_size' => null, 'plot_size_unit' => null, 'covered_area' => 1500, 'furnished' => true, 'description' => 'Fully furnished modern office space in Islamabad\'s business district. Reception, 3 cabins, and open area.' , 'features' => ['Central AC', 'Generator', 'Furnished', 'Parking'], 'community_amenities' => ['Security', 'Maintenance', 'Elevator'], 'nearby_places' => ['NAB Office', 'Serena Hotel', 'Convention Centre'], 'possession_status' => 'ready'],
            ['title' => '4-Bed House with Basement', 'type' => 'house', 'transaction_type' => 'rent', 'price' => 85000, 'city' => 'Lahore', 'sector_town' => 'Model Town', 'bedrooms' => 4, 'bathrooms' => 4, 'plot_size' => 600, 'plot_size_unit' => 'sqft', 'covered_area' => 4000, 'furnished' => false, 'description' => 'Spacious house in Model Town with basement, servant quarters, and large garden. Ideal for family.' , 'features' => ['Generator', 'Security', 'Garden'], 'community_amenities' => ['Park', 'Security'], 'nearby_places' => ['Model Town Market', 'School', 'Hospital'], 'possession_status' => 'ready'],
            ['title' => '2-Bed Apartment Near University', 'type' => 'flat', 'transaction_type' => 'rent', 'price' => 45000, 'city' => 'Islamabad', 'sector_town' => 'G-11/3', 'bedrooms' => 2, 'bathrooms' => 2, 'plot_size' => null, 'plot_size_unit' => null, 'covered_area' => 1200, 'furnished' => true, 'description' => 'Cozy furnished apartment near top universities. Ideal for students or young professionals.' , 'features' => ['Air Conditioning', 'Internet', 'Furnished'], 'community_amenities' => ['Security', 'Parking'], 'nearby_places' => ['IIUI', 'COMSATS', 'Giga Mall'], 'possession_status' => 'ready'],
        ];
        foreach ($propertiesData as $p) {
            Property::firstOrCreate(
                ['title' => $p['title']],
                [
                    'property_code' => 'PRP-' . strtoupper(substr((string)rand(100000, 999999), 0, 6)),
                    'type' => $p['type'],
                    'transaction_type' => $p['transaction_type'],
                    'status' => 'available',
                    'price' => $p['price'],
                    'currency' => 'PKR',
                    'city' => $p['city'],
                    'sector_town' => $p['sector_town'],
                    'location_address' => $p['sector_town'] . ', ' . $p['city'],
                    'bedrooms' => $p['bedrooms'],
                    'bathrooms' => $p['bathrooms'],
                    'plot_size' => $p['plot_size'],
                    'plot_size_unit' => $p['plot_size_unit'],
                    'covered_area' => $p['covered_area'],
                    'furnished' => $p['furnished'],
                    'description' => $p['description'],
                    'features' => $p['features'],
                    'community_amenities' => $p['community_amenities'],
                    'nearby_places' => $p['nearby_places'],
                    'owner_id' => $ownerIds[array_rand($ownerIds)],
                    'assigned_agent_id' => $agentIds[array_rand($agentIds)],
                    'possession_status' => $p['possession_status'],
                    'listed_date' => Carbon::now()->subDays(rand(10, 180)),
                    'views_count' => rand(50, 500),
                ]
            );
        }
        $this->command->info('12 Properties created.');

        // ─── DEALS ───────────────────────────────────────────
        $propertyIds = Property::pluck('id')->toArray();
        $clientIds = Client::pluck('id')->toArray();
        $dealsData = [
            ['deal_number' => 'DL-2026-001', 'sale_price' => 22000000, 'status' => 'completed'],
            ['deal_number' => 'DL-2026-002', 'sale_price' => 18500000, 'status' => 'completed'],
            ['deal_number' => 'DL-2026-003', 'sale_price' => 35000000, 'status' => 'in_progress'],
            ['deal_number' => 'DL-2026-004', 'sale_price' => 8500000, 'status' => 'offer_made'],
            ['deal_number' => 'DL-2026-005', 'sale_price' => 45000000, 'status' => 'in_progress'],
        ];
        foreach ($dealsData as $d) {
            Deal::firstOrCreate(
                ['deal_number' => $d['deal_number']],
                [
                    'property_id' => $propertyIds[array_rand($propertyIds)],
                    'buyer_id' => $clientIds[array_rand($clientIds)],
                    'seller_id' => $clientIds[array_rand($clientIds)],
                    'agent_id' => $agentIds[array_rand($agentIds)],
                    'type' => 'sale',
                    'status' => $d['status'],
                    'sale_price' => $d['sale_price'],
                    'token_amount' => $d['sale_price'] * 0.05,
                    'token_date' => Carbon::now()->subDays(rand(10, 60)),
                    'commission_percentage' => 2.50,
                    'commission_amount' => $d['sale_price'] * 0.025,
                    'agreement_date' => Carbon::now()->subDays(rand(5, 30)),
                    'possession_date' => Carbon::now()->addDays(rand(30, 90)),
                    'notes' => rand(0, 1) ? 'All documentation completed.' : null,
                ]
            );
        }
        $this->command->info('5 Deals created.');

        // ─── SETTINGS ────────────────────────────────────────
        $settings = [
            ['key' => 'brand_logo', 'value' => ''],
            ['key' => 'brand_name', 'value' => 'Prime Property Agency'],
            ['key' => 'currency', 'value' => 'PKR'],
            ['key' => 'tax_rate', 'value' => '16'],
            ['key' => 'admin_email', 'value' => 'admin@agency.com'],
            ['key' => 'company_phone', 'value' => '0800-12345'],
            ['key' => 'company_address', 'value' => 'Office 5, Plaza 100, Jinnah Avenue, Blue Area, Islamabad'],
        ];
        foreach ($settings as $s) {
            Setting::firstOrCreate(
                ['key' => $s['key']],
                ['value' => $s['value']]
            );
        }
        $this->command->info('Settings configured.');

        $this->command->info('─────────────────────────────');
        $this->command->info('Dummy data seeded successfully!');
        $this->command->info('Logins:');
        $this->command->info('  Admin: admin@agency.com / password');
        $this->command->info('  Alam:  alam@gmail.com / password');
        $this->command->info('  Rana:  rana@gmail.com / password');
    }
}
