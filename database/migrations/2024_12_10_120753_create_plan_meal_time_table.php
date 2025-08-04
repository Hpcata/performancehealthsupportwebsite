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
        // Check if table does not already exist before creating
        if (! Schema::hasTable('plan_meal_time')) {
            Schema::create('plan_meal_time', function (Blueprint $table) {
                $table->id();
                $table->foreignId('plan_id')->constrained()->onDelete('cascade');
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
        // Check if table exists before dropping
        if (Schema::hasTable('plan_meal_time')) {
            Schema::dropIfExists('plan_meal_time');
        }
    }
};