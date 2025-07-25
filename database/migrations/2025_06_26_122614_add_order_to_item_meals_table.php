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
        if (Schema::hasTable('item_meals') && ! Schema::hasColumn('item_meals', 'order')) {
            Schema::table('item_meals', function (Blueprint $table) {
                $table->integer('order')->nullable()->after('meal_id');
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
        if (Schema::hasTable('item_meals') && Schema::hasColumn('item_meals', 'order')) {
            Schema::table('item_meals', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};