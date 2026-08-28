<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->decimal('agency_amount', 14, 2)->default(0)->after('amount');
            $table->decimal('agent_amount', 14, 2)->default(0)->after('agency_amount');
            $table->string('source', 20)->nullable()->after('agent_amount'); // sale | rent
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropColumn(['agency_amount', 'agent_amount', 'source']);
        });
    }
};
