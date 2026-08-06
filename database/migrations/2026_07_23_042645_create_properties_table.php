<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique();
            $table->string('title');
            $table->enum('type', ['house', 'flat', 'plot', 'commercial', 'farmhouse', 'penthouse'])->default('house');
            $table->enum('transaction_type', ['sale', 'rent', 'lease'])->default('sale');
            $table->enum('status', ['available', 'under_offer', 'sold', 'rented', 'under_construction', 'off_market'])->default('available');
            $table->string('possession_status')->nullable();
            $table->year('possession_year')->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->decimal('price_per_sqft', 10, 2)->nullable();
            $table->string('currency', 10)->default('PKR');
            $table->text('location_address')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('city_id')->nullable()->index();
            $table->string('sector_town')->nullable();
            $table->string('block')->nullable();
            $table->decimal('plot_size', 10, 2)->nullable();
            $table->enum('plot_size_unit', ['marla', 'kanal', 'sqft', 'sqm', 'acre'])->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('covered_area', 12, 2)->nullable();
            $table->string('covered_area_unit', 20)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('kitchens')->default(1);
            $table->integer('floors')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('total_floors')->nullable();
            $table->json('additional_rooms')->nullable();
            $table->json('building_features')->nullable();
            $table->json('community_amenities')->nullable();
            $table->json('communication_features')->nullable();
            $table->boolean('furnished')->default(false);
            $table->integer('parking_spaces')->default(0);
            $table->json('features')->nullable();
            $table->text('nearby_landmarks')->nullable();
            $table->json('nearby_places')->nullable();
            $table->json('utilities')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->date('listed_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('views_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
