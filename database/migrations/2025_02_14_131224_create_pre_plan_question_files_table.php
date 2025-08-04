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
        if (! Schema::hasTable('pre_plan_question_files')) {
            Schema::create('pre_plan_question_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_pre_plan_id');
                $table->string('form_slug')->nullable();
                $table->string('question');
                $table->string('file_path')->nullable();
                $table->timestamps();

                $table->foreign('user_pre_plan_id')->references('id')->on('user_pre_plans')->onDelete('cascade');
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
        if (Schema::hasTable('pre_plan_question_files')) {
            Schema::dropIfExists('pre_plan_question_files');
        }
    }
};