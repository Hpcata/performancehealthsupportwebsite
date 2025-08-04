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
        if (! Schema::hasTable('category_subcategory')) {
            Schema::create('category_subcategory', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('sub_category_id');
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('sub_category_id')->references('id')->on('subcategories')->onDelete('cascade');

                // Optional composite unique constraint
                // $table->unique(['category_id', 'sub_category_id']);
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
        if (Schema::hasTable('category_subcategory')) {
            Schema::dropIfExists('category_subcategory');
        }
    }
};