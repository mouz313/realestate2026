<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_agreement_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');
            $table->date('due_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('total_due', 14, 2);
            $table->enum('status', ['pending', 'paid', 'overdue', 'waived'])->default('pending');
            $table->date('paid_date')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['rent_agreement_id', 'month', 'year']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
