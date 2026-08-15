<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'current_subscription_id')) {
            Schema::table('companies', function ($table) {
                $table->dropConstrainedForeignId('current_subscription_id');
            });
        }

        if (Schema::hasTable('subscriptions')) {
            Schema::dropIfExists('subscriptions');
        }

        if (Schema::hasTable('packages')) {
            Schema::dropIfExists('packages');
        }
    }

    public function down(): void
    {
        //
    }
};
