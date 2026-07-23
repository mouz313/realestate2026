<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('client_type', ['buyer', 'seller', 'tenant', 'landlord', 'both'])->default('both')->after('password');
            $table->string('cnic', 15)->nullable()->after('client_type');
            $table->boolean('cnic_verified')->default(false)->after('cnic');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['client_type', 'cnic', 'cnic_verified']);
        });
    }
};
