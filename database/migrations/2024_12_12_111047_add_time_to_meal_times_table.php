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
        Schema::table('meal_times', function (Blueprint $table) {
            $table->time('time')->nullable()->after('description'); // Adding time column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_times', function (Blueprint $table) {
            $table->dropColumn('time'); // Removing time column
        });
    }
};
