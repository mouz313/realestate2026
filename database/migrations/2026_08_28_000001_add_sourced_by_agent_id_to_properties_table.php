<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('sourced_by_agent_id')->nullable()->after('assigned_agent_id')
                ->constrained('agents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['sourced_by_agent_id']);
            $table->dropColumn('sourced_by_agent_id');
        });
    }
};
