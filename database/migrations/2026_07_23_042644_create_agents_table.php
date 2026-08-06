<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('cnic')->unique();
            $table->string('cnic_front')->nullable();
            $table->string('cnic_back')->nullable();
            $table->string('photo')->nullable();
            $table->text('address')->nullable();
            $table->string('license_number')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(2.50);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('type', ['in_house', 'freelance', 'partner'])->default('in_house');
            $table->date('join_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->string('languages')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('website')->nullable();
            $table->json('specializations')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        Schema::dropIfExists('agents');
    }
};
