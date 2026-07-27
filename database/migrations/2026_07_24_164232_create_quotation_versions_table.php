<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('discount_type', 10)->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('items_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_versions');
    }
};
