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

        $this->command->info('Admin user created: admin@agency.com / password');
    }
}
