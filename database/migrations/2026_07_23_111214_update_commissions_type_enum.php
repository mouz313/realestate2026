<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE commissions MODIFY COLUMN type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE commissions MODIFY COLUMN type ENUM('primary', 'co_agent', 'referral') NOT NULL DEFAULT 'primary'");
    }
};
