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
        Schema::table('items', function (Blueprint $table) {
            $table->integer('qty')->default(0); // Quantity with a default value of 0
            $table->string('alias')->nullable(); // Nullable string field for aliage
            $table->boolean('is_swiped')->default(false); // Boolean field for whether the item is swiped
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['qty', 'alias', 'is_swiped']); // Remove the added columns
        });
    }
};
