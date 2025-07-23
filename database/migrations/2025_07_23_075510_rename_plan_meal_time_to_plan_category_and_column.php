<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Rename the table
        if (Schema::hasTable('plan_meal_time')) {
            Schema::rename('plan_meal_time', 'plan_category');
        }

        // Step 2: Drop FK, rename column, and add new FK
        if (Schema::hasTable('plan_category')) {
            Schema::table('plan_category', function (Blueprint $table) {
                // Drop old foreign key constraint first
                if (Schema::hasColumn('plan_category', 'meal_time_id')) {
                    // $table->dropForeign(['meal_time_id']);
                }
            });

            Schema::table('plan_category', function (Blueprint $table) {
                // Rename column
                if (Schema::hasColumn('plan_category', 'meal_time_id')) {
                    $table->renameColumn('meal_time_id', 'category_id');
                }
            });

            Schema::table('plan_category', function (Blueprint $table) {
                // Add new foreign key
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('plan_category')) {
            Schema::table('plan_category', function (Blueprint $table) {
                // Drop the new foreign key
                if (Schema::hasColumn('plan_category', 'category_id')) {
                    $table->dropForeign(['category_id']);
                }
            });

            Schema::table('plan_category', function (Blueprint $table) {
                // Rename column back
                if (Schema::hasColumn('plan_category', 'category_id')) {
                    $table->renameColumn('category_id', 'meal_time_id');
                }
            });

            Schema::table('plan_category', function (Blueprint $table) {
                // Re-add original foreign key
                $table->foreign('meal_time_id')->references('id')->on('meal_times')->onDelete('cascade');
            });

            // Rename table back
            Schema::rename('plan_category', 'plan_meal_time');
        }
    }
};
