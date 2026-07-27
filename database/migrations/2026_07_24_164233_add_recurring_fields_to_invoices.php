<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('notes');
            $table->string('recurring_frequency', 20)->nullable()->after('is_recurring');
            $table->date('recurring_next_date')->nullable()->after('recurring_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'recurring_frequency', 'recurring_next_date']);
        });
    }
};
