<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('user_swap_items')) {
            Schema::create('user_swap_items', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_plan_id')->nullable();
                $table->bigInteger('user_category_id')->nullable();
                $table->bigInteger('user_sub_category_id')->nullable();
                $table->unsignedBigInteger('user_meal_id');
                $table->unsignedBigInteger('user_item_id');
                $table->timestamps();

                if (Schema::hasTable('user_meals')) {
                    $table->foreign('user_meal_id')->references('id')->on('user_meals');
                }

                if (Schema::hasTable('user_items')) {
                    $table->foreign('user_item_id')->references('id')->on('user_items');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_swap_items')) {
            Schema::dropIfExists('user_swap_items');
        }
    }
};
