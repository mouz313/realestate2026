<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(
            ['slug' => 'prime-property-agency'],
            [
                'name' => 'Prime Property Agency',
                'email' => 'admin@agency.com',
                'phone' => '0800-12345',
                'address' => 'Office 5, Plaza 100, Jinnah Avenue, Blue Area, Islamabad',
                'is_active' => true,
            ]
        );

        $this->command->info('Default company created: Prime Property Agency');
    }
}
