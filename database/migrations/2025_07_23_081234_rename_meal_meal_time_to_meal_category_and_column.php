<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Rename table
        if (Schema::hasTable('meal_meal_time')) {
            Schema::rename('meal_meal_time', 'meal_category');
        }

        // Step 2: Drop old FK, rename column, and add new FK
        if (Schema::hasTable('meal_category')) {
            Schema::table('meal_category', function (Blueprint $table) {
                // Drop old foreign key (if it exists)
                if (Schema::hasColumn('meal_category', 'meal_time_id')) {
                    $table->dropForeign(['meal_time_id']);
                }
            });

            Schema::table('meal_category', function (Blueprint $table) {
                // Rename column
                if (Schema::hasColumn('meal_category', 'meal_time_id')) {
                    $table->renameColumn('meal_time_id', 'category_id');
                }
            });

            Schema::table('meal_category', function (Blueprint $table) {
                // Add new foreign key
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('meal_category')) {
            Schema::table('meal_category', function (Blueprint $table) {
                // Drop new foreign key
                if (Schema::hasColumn('meal_category', 'category_id')) {
                    $table->dropForeign(['category_id']);
                }
            });

            Schema::table('meal_category', function (Blueprint $table) {
                // Rename column back
                if (Schema::hasColumn('meal_category', 'category_id')) {
                    $table->renameColumn('category_id', 'meal_time_id');
                }
            });

            Schema::table('meal_category', function (Blueprint $table) {
                // Re-add old foreign key
                $table->foreign('meal_time_id')->references('id')->on('meal_times')->onDelete('cascade');
            });

            // Rename table back
            Schema::rename('meal_category', 'meal_meal_time');
        }
    }
};
