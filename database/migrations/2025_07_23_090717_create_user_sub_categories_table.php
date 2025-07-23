<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('user_sub_categories')) {
            Schema::create('user_sub_categories', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->bigInteger('user_plan_id')->nullable();
                $table->unsignedBigInteger('user_category_id');
                $table->timestamps();

                if (Schema::hasTable('user_categories')) {
                    $table->foreign('user_category_id')->references('id')->on('user_categories');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_sub_categories')) {
            Schema::dropIfExists('user_sub_categories');
        }
    }
};