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
        // Drop the meal_times table if it exists
        if (Schema::hasTable('meal_times')) {
            // Schema::drop('meal_times');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate the table in case of rollback
        Schema::create('meal_times', function ($table) {
            // $table->id();
            // $table->string('title');
            // $table->text('description')->nullable();
            // $table->string('image')->nullable();
            // $table->timestamps();
        });
    }
};
