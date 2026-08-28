<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('furnished_type', ['furnished', 'semi_furnished', 'unfurnished'])
                ->nullable()->after('furnished');
            $table->enum('property_condition', ['new', 'resale'])
                ->nullable()->after('furnished_type');
            $table->year('year_built')->nullable()->after('property_condition');
            $table->decimal('road_width', 10, 2)->nullable()->after('year_built');
            $table->string('facing', 50)->nullable()->after('road_width');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['furnished_type', 'property_condition', 'year_built', 'road_width', 'facing']);
        });
    }
};
