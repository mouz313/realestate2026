<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        // Capture existing file paths before we change the column type.
        $existing = DB::table('posts')->whereNotNull('featured_image')->pluck('featured_image', 'id');

        Schema::table('posts', function (Blueprint $table) {
            $table->string('featured_image_mime')->nullable()->after('featured_image');
        });

        DB::statement('ALTER TABLE posts MODIFY featured_image LONGBLOB NULL');

        foreach ($existing as $id => $path) {
            if (Storage::disk('public')->exists($path)) {
                DB::table('posts')->where('id', $id)->update([
                    'featured_image' => Storage::disk('public')->get($path),
                    'featured_image_mime' => mime_content_type(Storage::disk('public')->path($path)) ?: 'image/jpeg',
                ]);
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE posts MODIFY featured_image VARCHAR(255) NULL');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('featured_image_mime');
        });
    }
};
