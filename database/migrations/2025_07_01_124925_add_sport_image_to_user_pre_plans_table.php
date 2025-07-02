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
        Schema::table('user_pre_plans', function (Blueprint $table) {
            $table->string('sport_image', 510)->nullable()->after('other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_pre_plans', function (Blueprint $table) {
            $table->string('sport_image')->nullable()->after('other');
        });
    }
};
