<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Free Demo', 'slug' => 'free-demo',
                'description' => 'Unlimited free demo package — great for evaluation.',
                'price' => 0, 'currency' => 'PKR', 'interval' => 'month',
                'max_employees' => 0, 'max_clients' => 0, 'max_properties' => 0,
                'trial_days' => 0, 'is_active' => true, 'sort_order' => 0,
            ],
            [
                'name' => 'Starter', 'slug' => 'starter',
                'description' => 'Perfect for small teams. 5 agents, 50 clients, 20 properties.',
                'price' => 5000, 'currency' => 'PKR', 'interval' => 'month',
                'max_employees' => 5, 'max_clients' => 50, 'max_properties' => 20,
                'trial_days' => 14, 'is_active' => true, 'sort_order' => 1,
            ],
            [
                'name' => 'Pro', 'slug' => 'pro',
                'description' => 'For growing agencies. 15 agents, 200 clients, unlimited properties.',
                'price' => 15000, 'currency' => 'PKR', 'interval' => 'month',
                'max_employees' => 15, 'max_clients' => 200, 'max_properties' => 0,
                'trial_days' => 30, 'is_active' => true, 'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise', 'slug' => 'enterprise',
                'description' => 'Custom yearly plan with priority support. Contact sales.',
                'price' => 60000, 'currency' => 'PKR', 'interval' => 'year',
                'max_employees' => 0, 'max_clients' => 0, 'max_properties' => 0,
                'trial_days' => 30, 'is_active' => true, 'sort_order' => 3,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(['slug' => $pkg['slug']], $pkg);
        }

        $free = Package::where('slug', 'free-demo')->first();

        $company = Company::first();
        if ($company && $free && ! $company->activeSubscription()) {
            Subscription::create([
                'company_id' => $company->id,
                'package_id' => $free->id,
                'status' => Subscription::STATUS_ACTIVE,
                'amount_paid' => 0,
                'currency' => 'PKR',
                'started_at' => now(),
                'ends_at' => null, // Free demo never expires
                'verified_by' => null,
                'verified_at' => now(),
                'previous_subscription_id' => null,
            ]);

            $company->update(['current_subscription_id' => $company->subscriptions()->latest('id')->first()->id]);
        }

        $this->command->info('Packages seeded: Free Demo, Starter, Pro, Enterprise.');
    }
}
