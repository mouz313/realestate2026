<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('deals', 'assigned_agent_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->foreignId('assigned_agent_id')->nullable()->after('property_id')->constrained('agents')->nullOnDelete();
                $table->index('assigned_agent_id');
            });
        }

        if (! Schema::hasColumn('clients', 'created_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('company_id')->constrained('users')->nullOnDelete();
                $table->index('created_by');
            });
        }

        if (! Schema::hasColumn('quotations', 'created_by')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('company_id')->constrained('users')->nullOnDelete();
                $table->index('created_by');
            });
        }

        if (! Schema::hasColumn('property_visits', 'agent_id')) {
            Schema::table('property_visits', function (Blueprint $table) {
                $table->foreignId('agent_id')->nullable()->after('property_id')->constrained('agents')->nullOnDelete();
                $table->index('agent_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('deals', 'assigned_agent_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->dropForeign(['assigned_agent_id']);
                $table->dropIndex(['assigned_agent_id']);
                $table->dropColumn('assigned_agent_id');
            });
        }

        if (Schema::hasColumn('clients', 'created_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropIndex(['created_by']);
                $table->dropColumn('created_by');
            });
        }

        if (Schema::hasColumn('quotations', 'created_by')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropIndex(['created_by']);
                $table->dropColumn('created_by');
            });
        }

        if (Schema::hasColumn('property_visits', 'agent_id')) {
            Schema::table('property_visits', function (Blueprint $table) {
                $table->dropForeign(['agent_id']);
                $table->dropIndex(['agent_id']);
                $table->dropColumn('agent_id');
            });
        }
    }
};
