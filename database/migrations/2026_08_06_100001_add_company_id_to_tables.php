<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users', 'agents', 'clients', 'properties', 'property_media', 'property_documents',
            'client_documents', 'deals', 'tokens', 'installment_plans', 'installments',
            'rent_agreements', 'rent_payments', 'rent_notices', 'quotations', 'quotation_items',
            'quotation_versions', 'invoices', 'invoice_items', 'payments', 'commissions',
            'agent_payouts', 'property_visits', 'expenses', 'activities', 'item_templates',
            'cities', 'contacts', 'settings', 'portal_actions',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->unsignedBigInteger('company_id')->nullable()->index();
            });
        }

        $defaultCompanyId = DB::table('companies')->orderBy('id')->value('id');

        if (! $defaultCompanyId) {
            $defaultCompanyId = DB::table('companies')->insertGetId([
                'name' => 'Prime Property Agency',
                'slug' => Str::slug('Prime Property Agency'),
                'email' => 'admin@agency.com',
                'phone' => '0800-12345',
                'address' => 'Office 5, Plaza 100, Jinnah Avenue, Blue Area, Islamabad',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            DB::table($table)->whereNull('company_id')->update(['company_id' => $defaultCompanyId]);
        }
    }

    public function down(): void
    {
        $tables = [
            'users', 'agents', 'clients', 'properties', 'property_media', 'property_documents',
            'client_documents', 'deals', 'tokens', 'installment_plans', 'installments',
            'rent_agreements', 'rent_payments', 'rent_notices', 'quotations', 'quotation_items',
            'quotation_versions', 'invoices', 'invoice_items', 'payments', 'commissions',
            'agent_payouts', 'property_visits', 'expenses', 'activities', 'item_templates',
            'cities', 'contacts', 'settings', 'portal_actions',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('company_id');
            });
        }
    }
};
