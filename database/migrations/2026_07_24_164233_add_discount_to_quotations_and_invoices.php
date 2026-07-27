<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('discount_type', 10)->nullable()->after('tax_amount');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_value');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('discount_type', 10)->nullable()->after('tax_amount');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_value');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'discount_amount']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'discount_amount']);
        });
    }
};
