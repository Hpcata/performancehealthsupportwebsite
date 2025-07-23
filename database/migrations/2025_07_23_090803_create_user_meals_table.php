<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('user_meals')) {
            Schema::create('user_meals', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_plan_id')->nullable();
                $table->bigInteger('user_category_id')->nullable();
                $table->bigInteger('user_sub_category_id')->nullable();
                $table->string('meal_name', 250)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_meals')) {
            Schema::dropIfExists('user_meals');
        }
    }
};
