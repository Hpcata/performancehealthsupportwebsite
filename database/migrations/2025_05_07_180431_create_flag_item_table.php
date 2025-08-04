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
        if (! Schema::hasTable('flag_item')) {
            Schema::create('flag_item', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('flag_id');
                $table->timestamps(); // Adds created_at and updated_at

                // Foreign keys
                $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
                $table->foreign('flag_id')->references('id')->on('flags')->onDelete('cascade');
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
        if (Schema::hasTable('flag_item')) {
            Schema::dropIfExists('flag_item');
        }
    }
};