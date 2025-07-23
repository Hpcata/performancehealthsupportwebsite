<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('user_plans')) {
            Schema::create('user_plans', function (Blueprint $table) {
                $table->id(); // auto-increment
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('plan_id');
                $table->bigInteger('modified_by')->nullable();
                $table->string('status', 50)->nullable();
                $table->boolean('is_mail_sent')->default(0);
                $table->timestamp('mail_sent_at')->nullable();
                $table->boolean('nutrition_info_flag')->default(1);
                $table->timestamps();

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users');
                }

                if (Schema::hasTable('plans')) {
                    $table->foreign('plan_id')->references('id')->on('plans');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_plans')) {
            Schema::dropIfExists('user_plans');
        }
    }
};
