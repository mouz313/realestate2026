<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $admin = User::where('email', 'admin@agency.com')->first();
        if ($admin) {
            $admin->update(['role' => 'admin', 'company_id' => $company?->id]);
        } else {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@agency.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'company_id' => $company?->id,
            ]);
        }

        if ($company) {
            $admin->assignRole('admin');
        }

        $this->command->info('Admin user created: admin@agency.com / password');
    }
}
