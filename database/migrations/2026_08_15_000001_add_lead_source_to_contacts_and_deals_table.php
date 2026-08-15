<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('lead_source')->nullable()->after('subject');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->string('lead_source')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('lead_source');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('lead_source');
        });
    }
};
