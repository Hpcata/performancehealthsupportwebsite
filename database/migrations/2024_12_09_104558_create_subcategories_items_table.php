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
        // Create the table only if it doesn't already exist
        if (! Schema::hasTable('subcategories_items')) {
            Schema::create('subcategories_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
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
        // Drop the table only if it exists
        if (Schema::hasTable('subcategories_items')) {
            Schema::dropIfExists('subcategories_items');
        }
    }
};