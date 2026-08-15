<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('property_type')->nullable()->after('property_title');
            $table->string('purpose')->nullable()->after('property_type');
            $table->string('city')->nullable()->after('purpose');
            $table->string('location')->nullable()->after('city');
            $table->decimal('budget_min', 14, 2)->nullable()->after('location');
            $table->decimal('budget_max', 14, 2)->nullable()->after('budget_min');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['property_type', 'purpose', 'city', 'location', 'budget_min', 'budget_max']);
        });
    }
};
