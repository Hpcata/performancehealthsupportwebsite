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
        Schema::create('user_pre_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->nullable(); // User ID (nullable if not provided)
            $table->unsignedBigInteger('user_id')->nullable(); // User ID (nullable if not provided)
            $table->date('dob')->nullable(); // Unique index for question (e.g., section_key format)
            $table->string('occupation', 210)->nullable(); // Unique index for question (e.g., section_key format)
            $table->string('address',510)->nullable(); // Unique index for question (e.g., section_key format)
            $table->string('referredBy',210)->nullable(); // Unique index for question (e.g., section_key format)
            $table->string('other',510)->nullable(); // Unique index for question (e.g., section_key format)
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_pre_plans');
    }
};
