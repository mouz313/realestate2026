<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('notes');
            $table->unsignedTinyInteger('experience_years')->nullable()->after('bio');
            $table->string('languages')->nullable()->after('experience_years');
            $table->string('facebook')->nullable()->after('languages');
            $table->string('twitter')->nullable()->after('facebook');
            $table->string('linkedin')->nullable()->after('twitter');
            $table->string('instagram')->nullable()->after('linkedin');
            $table->string('website')->nullable()->after('instagram');
            $table->json('specializations')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn([
                'bio', 'experience_years', 'languages',
                'facebook', 'twitter', 'linkedin', 'instagram',
                'website', 'specializations',
            ]);
        });
    }
};
