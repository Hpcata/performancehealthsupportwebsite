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
        if (! Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->enum('type', ['percentage', 'fixed']);
                $table->decimal('value', 10, 2);
                $table->decimal('min_order_value', 10, 2)->nullable();
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->integer('max_uses')->default(0);
                $table->integer('uses_per_user')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
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
        if (Schema::hasTable('coupons')) {
            Schema::dropIfExists('coupons');
        }
    }
};