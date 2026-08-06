<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_agreement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('clients')->cascadeOnDelete();
            $table->date('notice_date');
            $table->date('move_out_date');
            $table->enum('notice_type', ['tenant', 'landlord'])->default('tenant');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_notices');
    }
};
