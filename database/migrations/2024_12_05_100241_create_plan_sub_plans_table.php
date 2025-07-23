<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plan_sub_plans')) {
            Schema::create('plan_sub_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id');
                $table->unsignedBigInteger('sub_plan_id');
                $table->timestamps();

                // Optional: add foreign key constraints
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
                $table->foreign('sub_plan_id')->references('id')->on('plans')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_sub_plans');
    }
};
