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
        if (Schema::hasTable('sport_trackings')) {
            Schema::table('sport_trackings', function (Blueprint $table) {
                if (! Schema::hasColumn('sport_trackings', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }

                if (! Schema::hasColumn('sport_trackings', 'email')) {
                    $table->string('email')->nullable()->after('name');
                }
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
        if (Schema::hasTable('sport_trackings')) {
            Schema::table('sport_trackings', function (Blueprint $table) {
                if (Schema::hasColumn('sport_trackings', 'name')) {
                    $table->dropColumn('name');
                }

                if (Schema::hasColumn('sport_trackings', 'email')) {
                    $table->dropColumn('email');
                }
            });
        }
    }
};