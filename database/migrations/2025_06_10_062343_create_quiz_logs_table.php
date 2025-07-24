<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('quiz_logs')) {
            Schema::create('quiz_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address');
                $table->string('user_agent')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email')->nullable();
                $table->json('completed_steps')->nullable();
                $table->integer('free_quiz_clicks')->default(0);
                $table->boolean('completed_without_email')->default(false);
                $table->boolean('completed_with_email')->default(false);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('quiz_logs')) {
            Schema::dropIfExists('quiz_logs');
        }
    }
};