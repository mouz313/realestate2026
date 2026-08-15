<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
            $table->unsignedBigInteger('rent_agreement_id')->nullable()->after('invoice_id');
            $table->foreign('rent_agreement_id')->references('id')->on('rent_agreements')->cascadeOnDelete();
            $table->index('rent_agreement_id');
        });

        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->decimal('deposit_received_amount', 14, 2)->default(0)->after('security_deposit');
            $table->date('deposit_received_date')->nullable()->after('deposit_received_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['rent_agreement_id']);
            $table->dropIndex(['rent_agreement_id']);
            $table->dropColumn('rent_agreement_id');
        });

        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->dropColumn(['deposit_received_amount', 'deposit_received_date']);
        });
    }
};