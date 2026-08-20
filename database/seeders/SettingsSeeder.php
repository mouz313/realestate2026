<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'currency', 'value' => 'PKR'],
            ['key' => 'bank_name', 'value' => ''],
            ['key' => 'bank_account_title', 'value' => ''],
            ['key' => 'bank_iban', 'value' => ''],
            ['key' => 'raast_iban', 'value' => ''],
            ['key' => 'jazzcash_merchant_id', 'value' => ''],
            ['key' => 'jazzcash_password', 'value' => ''],
            ['key' => 'jazzcash_secret_salt', 'value' => ''],
            ['key' => 'jazzcash_sandbox', 'value' => '1'],
            ['key' => 'easypaisa_merchant_id', 'value' => ''],
            ['key' => 'easypaisa_secret_key', 'value' => ''],
            ['key' => 'easypaisa_sandbox', 'value' => '1'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }
    }
}
