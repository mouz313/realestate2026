<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('possession_status')->nullable()->after('status');
            $table->year('possession_year')->nullable()->after('possession_status');
            $table->decimal('covered_area', 12, 2)->nullable()->after('land_area');
            $table->string('covered_area_unit', 20)->nullable()->after('covered_area');
            $table->integer('floor_number')->nullable()->after('floors');
            $table->integer('total_floors')->nullable()->after('floor_number');
            $table->json('additional_rooms')->nullable()->after('total_floors');
            $table->json('building_features')->nullable()->after('additional_rooms');
            $table->json('community_amenities')->nullable()->after('building_features');
            $table->json('communication_features')->nullable()->after('community_amenities');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'possession_status', 'possession_year',
                'covered_area', 'covered_area_unit',
                'floor_number', 'total_floors',
                'additional_rooms', 'building_features',
                'community_amenities', 'communication_features',
            ]);
        });
    }
};
