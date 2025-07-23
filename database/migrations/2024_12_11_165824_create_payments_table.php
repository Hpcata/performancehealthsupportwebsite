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
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->integer('plan_id');
                $table->decimal('price', 10, 2);
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('payment_intent_id')->unique();
                $table->string('status');
                $table->timestamps();

                // Optional: Add foreign key if plan_id references plans table
                // $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
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
        if (Schema::hasTable('payments')) {
            Schema::dropIfExists('payments');
        }
    }
};
