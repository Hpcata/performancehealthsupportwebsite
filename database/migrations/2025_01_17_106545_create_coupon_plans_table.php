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
        if (! Schema::hasTable('coupon_plans')) {
            Schema::create('coupon_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('coupon_id');
                $table->unsignedBigInteger('plan_id');
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

                // Optional: prevent duplicate coupon-plan relationships
                $table->unique(['coupon_id', 'plan_id']);
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
        if (Schema::hasTable('coupon_plans')) {
            Schema::table('coupon_plans', function (Blueprint $table) {
                // Drop foreign key constraints
                $table->dropForeign(['coupon_id']);
                $table->dropForeign(['plan_id']);

                // Drop unique index
                $table->dropUnique(['coupon_id', 'plan_id']);
            });

            Schema::dropIfExists('coupon_plans');
        }
    }
};