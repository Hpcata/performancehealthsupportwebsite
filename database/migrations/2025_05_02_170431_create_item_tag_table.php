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
        if (! Schema::hasTable('item_tag')) {
            Schema::create('item_tag', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps(); // Adds created_at and updated_at

                // Foreign keys
                $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

                // Composite primary key (optional, if needed)
                // $table->primary(['item_id', 'tag_id']);
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
        if (Schema::hasTable('item_tag')) {
            Schema::dropIfExists('item_tag');
        }
    }
};