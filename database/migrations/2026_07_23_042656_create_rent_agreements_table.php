<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('rent_amount', 14, 2);
            $table->decimal('security_deposit', 14, 2)->default(0);
            $table->boolean('deposit_received')->default(false);
            $table->boolean('deposit_returned')->default(false);
            $table->integer('notice_period_days')->default(30);
            $table->decimal('late_fee_per_day', 10, 2)->default(0);
            $table->decimal('rent_increase_percent', 5, 2)->nullable();
            $table->enum('rent_increase_frequency', ['yearly', 'none'])->default('none');
            $table->string('agreement_doc')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated', 'renewed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_agreements');
    }
};
