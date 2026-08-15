<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->morphs('notifiable');
                $table->string('type');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'notification_prefs')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('notification_prefs')->nullable()->after('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'notification_prefs')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('notification_prefs');
            });
        }
    }
};