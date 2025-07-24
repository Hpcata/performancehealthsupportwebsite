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
        if (! Schema::hasTable('pre_plan_details')) {
            Schema::create('pre_plan_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_pre_plan_id')->nullable();
                $table->string('form_name');
                $table->string('form_slug');
                $table->text('question');
                $table->json('answer')->nullable();
                $table->timestamps();

                if (Schema::hasTable('user_pre_plans')) {
                    $table->foreign('user_pre_plan_id')
                        ->references('id')
                        ->on('user_pre_plans')
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
        if (Schema::hasTable('pre_plan_details')) {
            Schema::dropIfExists('pre_plan_details');
        }
    }
};