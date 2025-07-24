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
        // Check if table doesn't exist before creating
        if (! Schema::hasTable('meals')) {
            Schema::create('meals', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('image')->nullable();
                $table->text('description')->nullable();
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
        // Drop table only if it exists
        if (Schema::hasTable('meals')) {
            Schema::dropIfExists('meals');
        }
    }
};