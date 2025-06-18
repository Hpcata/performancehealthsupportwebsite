<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_meals', function (Blueprint $table) {
            // First, copy meal_id values to id column
            DB::statement('UPDATE user_meals SET id = meal_id');
            
            // Then drop the meal_id column
            $table->dropColumn('meal_id');
        });
    }

    public function down()
    {
        Schema::table('user_meals', function (Blueprint $table) {
            // Add back the meal_id column
            $table->unsignedBigInteger('meal_id')->after('user_plan_id');
            
            // Copy id values back to meal_id
            DB::statement('UPDATE user_meals SET meal_id = id');
        });
    }
}; 