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
        if (! Schema::hasTable('category_sport_game')) {
            Schema::create('category_sport_game', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sport_category_id')->constrained()->onDelete('cascade');
                $table->foreignId('sport_game_id')->constrained()->onDelete('cascade');
                $table->string('image_path')->nullable(); // 👈 Store image for this relation
                $table->timestamps();
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
        if (Schema::hasTable('category_sport_game')) {
            Schema::dropIfExists('category_sport_game');
        }
    }
};