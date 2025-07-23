<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('user_categories')) {
            Schema::create('user_categories', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->unsignedBigInteger('user_plan_id');
                $table->timestamps();

                if (Schema::hasTable('user_plans')) {
                    $table->foreign('user_plan_id')->references('id')->on('user_plans');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_categories')) {
            Schema::dropIfExists('user_categories');
        }
    }
};
