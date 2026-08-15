<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Re-anchor the legacy users.role column to a canonical enum so bad values
        // cannot leak through. RBAC roles table is the source of truth; this column
        // is only a cache for display / quick queries.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'admin', 'super_admin', 'staff', 'agent') NOT NULL DEFAULT 'agent'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'agent'");
        }
    }
};