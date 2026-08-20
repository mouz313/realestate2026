<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Convert type to VARCHAR so we can freely normalize values
        DB::statement("ALTER TABLE properties MODIFY `type` VARCHAR(50) NOT NULL DEFAULT 'house'");

        // 2. Normalize old values into the new vocabulary
        DB::statement("UPDATE properties SET type = 'office' WHERE type = 'commercial'");
        DB::statement("UPDATE properties SET type = 'flat' WHERE type = 'penthouse'");
        DB::statement("UPDATE properties SET transaction_type = 'rent' WHERE transaction_type = 'lease'");
        DB::statement("UPDATE properties SET status = 'available' WHERE status IN ('under_offer','under_construction','off_market')");

        // 3. Rename type -> category with the new enum
        DB::statement("ALTER TABLE properties CHANGE `type` `category` ENUM('house','plot','farmhouse','agricultural_land','flat','studio_apartment','office','shop') NOT NULL DEFAULT 'house'");
        // 4. New transaction_type enum
        DB::statement("ALTER TABLE properties MODIFY `transaction_type` ENUM('sale','buy','rent','installment') NOT NULL DEFAULT 'sale'");
        // 5. New status enum
        DB::statement("ALTER TABLE properties MODIFY `status` ENUM('available','rented','sold') NOT NULL DEFAULT 'available'");
    }
    public function down(): void
    {
        DB::statement("ALTER TABLE properties CHANGE `category` `type` ENUM('house','flat','plot','commercial','farmhouse','penthouse') NOT NULL DEFAULT 'house'");
        DB::statement("ALTER TABLE properties MODIFY `transaction_type` ENUM('sale','rent','lease') NOT NULL DEFAULT 'sale'");
        DB::statement("ALTER TABLE properties MODIFY `status` ENUM('available','under_offer','sold','rented','under_construction','off_market') NOT NULL DEFAULT 'available'");
    }
};
