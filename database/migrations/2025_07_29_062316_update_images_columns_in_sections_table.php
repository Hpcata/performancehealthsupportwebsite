<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            // Add new columns
            if (!Schema::hasColumn('sections', 'image')) {
                $table->json('image')->nullable()->after('image');
            }

            if (!Schema::hasColumn('sections', 'banner_image')) {
                $table->json('banner_image')->nullable()->after('image');
            }
        });

        // Optional: Migrate data from old `image` column to `images` JSON array
        DB::table('sections')->whereNotNull('image')->update([
            'image' => DB::raw("JSON_ARRAY(image)")
        ]);

        // Drop the old `image` column
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->string('image')->nullable()->after('id');
            $table->dropColumn('image');
            $table->dropColumn('banner_image');
        });

        // Optional: Move data back if needed
    }
};
