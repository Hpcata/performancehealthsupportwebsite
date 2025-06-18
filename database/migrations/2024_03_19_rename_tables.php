<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename meal_times to categories
        Schema::rename('meal_times', 'categories');
        
        // Rename categories to sub_categories
        Schema::rename('categories', 'sub_categories');
        
        // Rename pivot tables
        Schema::rename('plan_meal_time', 'plan_category');
        Schema::rename('meal_meal_time', 'meal_category');
        Schema::rename('category_mealtime', 'subcategory_category');
        
        // First rename existing user_categories to user_sub_categories
        Schema::rename('user_categories', 'user_sub_categories');
        
        // Then rename user_meal_times to user_categories
        Schema::rename('user_meal_times', 'user_categories');
        
        // Update foreign key columns
        Schema::table('user_categories', function (Blueprint $table) {
            $table->renameColumn('meal_time_id', 'category_id');
        });
        
        Schema::table('plan_category', function (Blueprint $table) {
            $table->renameColumn('meal_time_id', 'category_id');
        });
        
        Schema::table('meal_category', function (Blueprint $table) {
            $table->renameColumn('meal_time_id', 'category_id');
        });
        
        Schema::table('subcategory_category', function (Blueprint $table) {
            $table->renameColumn('meal_time_id', 'category_id');
            $table->renameColumn('category_id', 'sub_category_id');
        });
    }

    public function down()
    {
        // Reverse the changes
        Schema::rename('categories', 'meal_times');
        Schema::rename('sub_categories', 'categories');
        
        Schema::rename('plan_category', 'plan_meal_time');
        Schema::rename('meal_category', 'meal_meal_time');
        Schema::rename('subcategory_category', 'category_mealtime');
        
        // First rename user_categories back to user_meal_times
        Schema::rename('user_categories', 'user_meal_times');
        
        // Then rename user_sub_categories back to user_categories
        Schema::rename('user_sub_categories', 'user_categories');
        
        Schema::table('user_meal_times', function (Blueprint $table) {
            $table->renameColumn('category_id', 'meal_time_id');
        });
        
        Schema::table('plan_meal_time', function (Blueprint $table) {
            $table->renameColumn('category_id', 'meal_time_id');
        });
        
        Schema::table('meal_meal_time', function (Blueprint $table) {
            $table->renameColumn('category_id', 'meal_time_id');
        });
        
        Schema::table('category_mealtime', function (Blueprint $table) {
            $table->renameColumn('category_id', 'meal_time_id');
            $table->renameColumn('sub_category_id', 'category_id');
        });
    }
}; 