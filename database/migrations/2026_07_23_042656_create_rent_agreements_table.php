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
            $table->foreignId('renewed_from_id')->nullable()->constrained('rent_agreements')->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('rent_amount', 14, 2);
            $table->decimal('security_deposit', 14, 2)->default(0);
            $table->string('payment_frequency', 50)->nullable();
            $table->boolean('deposit_received')->default(false);
            $table->boolean('deposit_returned')->default(false);
            $table->decimal('deposit_deductions', 14, 2)->default(0);
            $table->text('deposit_deduction_notes')->nullable();
            $table->date('deposit_returned_date')->nullable();
            $table->integer('notice_period_days')->default(30);
            $table->decimal('late_fee_per_day', 10, 2)->default(0);
            $table->decimal('rent_increase_percent', 5, 2)->nullable();
            $table->string('rent_increase_frequency', 50)->nullable();
            $table->string('agreement_doc')->nullable();
            $table->string('status', 50)->default('active');
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_agreements');
    }
};
