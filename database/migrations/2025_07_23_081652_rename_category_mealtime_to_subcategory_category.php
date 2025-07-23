<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Rename table
        if (Schema::hasTable('category_mealtime')) {
            Schema::rename('category_mealtime', 'subcategory_category');
        }

        // Step 2: Rename columns and foreign keys
        if (Schema::hasTable('subcategory_category')) {
            Schema::table('subcategory_category', function (Blueprint $table) {
                if (Schema::hasColumn('subcategory_category', 'meal_time_id')) {
                    $table->renameColumn('meal_time_id', 'sub_category_id');
                }
            });

            Schema::table('subcategory_category', function (Blueprint $table) {
                // Add new foreign keys
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('subcategory_category')) {
            
            Schema::table('subcategory_category', function (Blueprint $table) {
                $table->renameColumn('sub_category_id', 'meal_time_id');
            });

            Schema::table('subcategory_category', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('meal_time_id')->references('id')->on('meal_times')->onDelete('cascade');
            });

            Schema::rename('subcategory_category', 'category_mealtime');
        }
    }
};
