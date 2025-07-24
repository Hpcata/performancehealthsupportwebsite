<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create table only if it doesn't already exist
        if (! Schema::hasTable('sport_categories')) {
            Schema::create('sport_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Drop table only if it exists
        if (Schema::hasTable('sport_categories')) {
            Schema::dropIfExists('sport_categories');
        }
    }
};