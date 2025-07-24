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
        if (! Schema::hasTable('category_mealtime')) {
            Schema::create('category_mealtime', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('meal_time_id');
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('meal_time_id')->references('id')->on('meal_times')->onDelete('cascade');

                // Optional composite unique constraint (if each combination must be unique)
                // $table->unique(['category_id', 'meal_time_id']);
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
        if (Schema::hasTable('category_mealtime')) {
            Schema::dropIfExists('category_mealtime');
        }
    }
};