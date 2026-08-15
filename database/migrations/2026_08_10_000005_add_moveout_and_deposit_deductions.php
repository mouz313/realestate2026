<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_deposit_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rent_agreement_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['damage', 'unpaid_rent', 'utilities', 'other'])->default('damage');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('rent_agreement_id');
        });

        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->date('possession_returned_date')->nullable()->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->dropColumn('possession_returned_date');
        });

        Schema::dropIfExists('rent_deposit_deductions');
    }
};