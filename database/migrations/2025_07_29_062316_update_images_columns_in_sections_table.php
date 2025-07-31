<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add image_json and banner_image columns
        Schema::table('sections', function (Blueprint $table) {
            if (! Schema::hasColumn('sections', 'image_json')) {
                $table->json('image_json')->nullable()->after('order');
            }

            if (! Schema::hasColumn('sections', 'banner_image')) {
                $table->json('banner_image')->nullable()->after('image_json');
            }
        });

        // Step 2: Only update if 'image' column exists
        if (Schema::hasColumn('sections', 'image')) {
            // Copy data from 'image' (string) into 'image_json' (JSON array)
            DB::statement("UPDATE sections SET image_json = JSON_ARRAY(image) WHERE image IS NOT NULL");

            // Drop old 'image' column
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        // Step 3: Add final 'image' column as JSON
        if (! Schema::hasColumn('sections', 'image')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->json('image')->nullable()->after('order');
            });

            // Copy data from image_json to new image column
            DB::statement("UPDATE sections SET image = image_json WHERE image_json IS NOT NULL");
        }

        // Step 4: Drop temporary image_json column
        if (Schema::hasColumn('sections', 'image_json')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('image_json');
            });
        }
    }

    public function down(): void
    {
        // Step 1: Add back string image column
        Schema::table('sections', function (Blueprint $table) {
            if (! Schema::hasColumn('sections', 'image')) {
                $table->string('image')->nullable()->after('id');
            }
        });

        // Step 2: Extract first item from JSON array
        if (Schema::hasColumn('sections', 'image')) {
            DB::statement("UPDATE sections SET image = JSON_UNQUOTE(JSON_EXTRACT(image, '$[0]')) WHERE image IS NOT NULL");
        }

        // Step 3: Drop JSON image and banner_image columns
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'image')) {
                $table->dropColumn('image');
            }

            if (Schema::hasColumn('sections', 'banner_image')) {
                $table->dropColumn('banner_image');
            }
        });
    }
};