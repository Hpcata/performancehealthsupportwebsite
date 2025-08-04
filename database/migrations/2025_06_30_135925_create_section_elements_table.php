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
        if (! Schema::hasTable('section_elements')) {
            Schema::create('section_elements', function (Blueprint $table) {
                $table->id();
                $table->string('section_element_name', 100)->unique();
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
        if (Schema::hasTable('section_elements')) {
            Schema::dropIfExists('section_elements');
        }
    }
};