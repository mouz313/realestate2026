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
            $table->string('phone');
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
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
