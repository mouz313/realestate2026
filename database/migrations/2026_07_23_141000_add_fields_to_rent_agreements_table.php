<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->string('payment_frequency', 50)->nullable()->after('security_deposit');
            $table->text('terms')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->dropColumn(['payment_frequency', 'terms']);
        });
    }
};
