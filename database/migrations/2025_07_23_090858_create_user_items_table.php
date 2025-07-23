<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('user_items')) {
            Schema::create('user_items', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_plan_id')->nullable();
                $table->bigInteger('user_category_id')->nullable();
                $table->bigInteger('user_sub_category_id')->nullable();
                $table->unsignedBigInteger('user_meal_id');
                $table->string('qty', 210)->nullable();
                $table->timestamps();

                if (Schema::hasTable('user_meals')) {
                    $table->foreign('user_meal_id')->references('id')->on('user_meals');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_items')) {
            Schema::dropIfExists('user_items');
        }
    }
};
