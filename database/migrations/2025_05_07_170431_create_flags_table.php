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
        if (! Schema::hasTable('flags')) {
            Schema::create('flags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                // $table->string('icon')->nullable(); // Path to uploaded icon file
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
        if (Schema::hasTable('flags')) {
            Schema::dropIfExists('flags');
        }
    }
};