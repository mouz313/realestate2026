<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gateway_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->morphs('recipient');
            $table->string('gateway', 50);
            $table->string('currency', 10)->default('PKR');
            $table->decimal('amount', 16, 2);
            $table->decimal('charged_amount', 16, 2)->nullable();
            $table->string('order_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->default('pending')->index();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['gateway', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_payments');
    }
};