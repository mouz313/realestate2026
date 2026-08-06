<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@agency.com')->first();
        if ($admin) {
            $admin->update(['role' => 'admin']);
        } else {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@agency.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        $superAdmin = User::where('email', 'superadmin@agency.com')->first();
        if (! $superAdmin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@agency.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]);
        }

        $this->command->info('Admin user created: admin@agency.com / password');
        $this->command->info('Super admin created: superadmin@agency.com / password');
    }
}
