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
        if (! Schema::hasTable('user_pre_plans')) {
            Schema::create('user_pre_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('dob')->nullable();
                $table->string('occupation', 210)->nullable();
                $table->string('address', 510)->nullable();
                $table->string('referredBy', 210)->nullable();
                $table->string('other', 510)->nullable();
                $table->timestamps();

                // Add foreign keys only if the related tables exist
                if (Schema::hasTable('payments')) {
                    $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
                }

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        if (Schema::hasTable('user_pre_plans')) {
            Schema::dropIfExists('user_pre_plans');
        }
    }
};