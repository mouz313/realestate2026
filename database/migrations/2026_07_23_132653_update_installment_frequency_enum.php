<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE installment_plans MODIFY frequency VARCHAR(50) NOT NULL DEFAULT 'monthly'");
        } elseif ($driver === 'sqlite') {
            DB::statement("ALTER TABLE installment_plans ADD COLUMN frequency_new VARCHAR(50) NOT NULL DEFAULT 'monthly'");
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE installment_plans MODIFY frequency ENUM('monthly','quarterly','biannual','custom') NOT NULL DEFAULT 'monthly'");
        }
    }
};
