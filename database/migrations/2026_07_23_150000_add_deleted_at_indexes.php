<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->index('deleted_at');
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->index('deleted_at');
        });
        Schema::table('deals', function (Blueprint $table) {
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('deals', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });
    }
};
