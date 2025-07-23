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
        if (! Schema::hasTable('meal_subcategory')) {
            Schema::create('meal_subcategory', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('meal_id');
                $table->unsignedBigInteger('sub_category_id');
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
                $table->foreign('sub_category_id')->references('id')->on('subcategories')->onDelete('cascade');

                // Optional composite unique constraint
                // $table->unique(['meal_id', 'sub_category_id']);
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
        if (Schema::hasTable('meal_subcategory')) {
            Schema::dropIfExists('meal_subcategory');
        }
    }
};