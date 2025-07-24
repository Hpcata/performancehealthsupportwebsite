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
        if (! Schema::hasTable('meal_tag')) {
            Schema::create('meal_tag', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('meal_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps(); // Adds created_at and updated_at

                // Foreign keys
                $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

                // Composite primary key (optional)
                // $table->primary(['meal_id', 'tag_id']);
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
        if (Schema::hasTable('meal_tag')) {
            Schema::dropIfExists('meal_tag');
        }
    }
};