<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->after('co_agent_id')->constrained('contacts')->nullOnDelete();
        });

        Schema::table('property_visits', function (Blueprint $table) {
            $table->foreignId('deal_id')->nullable()->after('contact_id')->constrained('deals')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('property_visits', function (Blueprint $table) {
            $table->dropForeign(['deal_id']);
            $table->dropColumn('deal_id');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
        });
    }
};
