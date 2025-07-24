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
        if (! Schema::hasTable('sport_trackings')) {
            Schema::create('sport_trackings', function (Blueprint $table) {
                $table->id();
                $table->string('sport');
                $table->string('state');
                $table->string('sport_game')->nullable();
                $table->string('ip_address')->nullable();
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
        if (Schema::hasTable('sport_trackings')) {
            Schema::dropIfExists('sport_trackings');
        }
    }
};