<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enquiry (Contact) has been merged into Call Log (Lead).
     * Drop the redundant contacts table and repoint contact_id on
     * deals and property_visits to call_log_id (FK -> call_logs).
     */
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
            $table->foreignId('call_log_id')->nullable()->constrained('call_logs')->nullOnDelete();
        });

        Schema::table('property_visits', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
            $table->foreignId('call_log_id')->nullable()->constrained('call_logs')->nullOnDelete();
        });

        Schema::dropIfExists('contacts');
    }

    public function down(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('property_type')->nullable();
            $table->string('purpose')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            $table->decimal('budget_min', 14, 2)->nullable();
            $table->decimal('budget_max', 14, 2)->nullable();
            $table->string('lead_source')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('open');
            $table->timestamp('read_at')->nullable();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['call_log_id']);
            $table->dropColumn('call_log_id');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
        });

        Schema::table('property_visits', function (Blueprint $table) {
            $table->dropForeign(['call_log_id']);
            $table->dropColumn('call_log_id');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
        });
    }
};
