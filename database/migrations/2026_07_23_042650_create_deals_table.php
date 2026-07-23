<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('deal_number')->unique();
            $table->enum('type', ['sale', 'rent', 'lease'])->default('sale');
            $table->enum('status', ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'])->default('inquiry');
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('co_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->decimal('sale_price', 14, 2)->nullable();
            $table->decimal('token_amount', 14, 2)->nullable();
            $table->date('token_date')->nullable();
            $table->decimal('commission_percentage', 5, 2)->default(2.50);
            $table->decimal('commission_amount', 14, 2)->nullable();
            $table->decimal('agent_commission', 14, 2)->nullable();
            $table->decimal('agency_share', 14, 2)->nullable();
            $table->date('agreement_date')->nullable();
            $table->date('possession_date')->nullable();
            $table->json('payment_plan')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
