<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 50);
            $table->string('alternate_phone', 50)->nullable();
            $table->string('lead_source')->default('phone_call');
            $table->string('category')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('city_id')->nullable()->index();
            $table->string('location')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->decimal('budget_min', 14, 2)->nullable();
            $table->decimal('budget_max', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('call_datetime')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('status')->default('new'); // new|contacted|callback|matched|converted|lost
            $table->timestamp('matched_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
