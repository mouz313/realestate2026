<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Safely remove unused/dead modules. Their original create-migrations are
    // intentionally left in history; this drop runs after add_company_id_to_tables
    // so a fresh `migrate`/`migrate:fresh` still works.
    public function up(): void
    {
        Schema::dropIfExists('gateway_payments');
        Schema::dropIfExists('client_documents');
        Schema::dropIfExists('quotation_versions');
        Schema::dropIfExists('referrals');
    }

    public function down(): void
    {
        // Intentionally not restoring: these modules are being removed for good.
    }
};
