<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_item_meals', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['user_id', 'meal_id']);
        });

        Schema::table('user_plans', function (Blueprint $table) {
            $table->index(['user_id', 'plan_id']);
        });

        Schema::table('user_categories', function (Blueprint $table) {
            $table->index('user_plan_id');
            $table->index('category_id');
        });

        Schema::table('user_item_swaps', function (Blueprint $table) {
            $table->index(['user_id', 'meal_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_item_meals', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'meal_id']);
        });

        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'plan_id']);
        });

        Schema::table('user_categories', function (Blueprint $table) {
            $table->dropIndex(['user_plan_id']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('user_item_swaps', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'meal_id', 'item_id']);
        });
    }
};