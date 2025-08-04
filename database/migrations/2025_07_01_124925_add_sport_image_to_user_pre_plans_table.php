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
        if (Schema::hasTable('user_pre_plans') && ! Schema::hasColumn('user_pre_plans', 'sport_image')) {
            Schema::table('user_pre_plans', function (Blueprint $table) {
                $table->string('sport_image', 510)->nullable()->after('other');
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
        if (Schema::hasTable('user_pre_plans') && Schema::hasColumn('user_pre_plans', 'sport_image')) {
            Schema::table('user_pre_plans', function (Blueprint $table) {
                $table->dropColumn('sport_image');
            });
        }
    }
};