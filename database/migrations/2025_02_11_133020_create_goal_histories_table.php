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
        if (! Schema::hasTable('goal_histories')) {
            Schema::create('goal_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->string('type'); // 'goal' or 'challenge'
                $table->string('question');
                $table->json('answer');
                $table->timestamps();

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
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
        if (Schema::hasTable('goal_histories')) {
            Schema::dropIfExists('goal_histories');
        }
    }
};
