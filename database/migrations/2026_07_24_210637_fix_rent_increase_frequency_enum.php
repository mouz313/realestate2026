<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->string('rent_increase_frequency', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rent_agreements', function (Blueprint $table) {
            $table->enum('rent_increase_frequency', ['yearly', 'none', 'monthly', 'quarterly'])->default('none')->change();
        });
    }
};
