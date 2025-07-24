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
        if (! Schema::hasTable('meal_meal_time')) {
            Schema::create('meal_meal_time', function (Blueprint $table) {
                $table->id();
                $table->foreignId('meal_id')->constrained()->onDelete('cascade');
                $table->foreignId('meal_time_id')->constrained()->onDelete('cascade');
                $table->timestamps();
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
        if (Schema::hasTable('meal_meal_time')) {
            Schema::dropIfExists('meal_meal_time');
        }
    }
};