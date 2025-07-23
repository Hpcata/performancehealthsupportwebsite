<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('goal_histories')) {
            Schema::table('goal_histories', function (Blueprint $table) {
                if (!Schema::hasColumn('goal_histories', 'start_date')) {
                    $table->date('start_date')->nullable()->after('answer');
                }
                if (!Schema::hasColumn('goal_histories', 'end_date')) {
                    $table->date('end_date')->nullable()->after('start_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('goal_histories')) {
            Schema::table('goal_histories', function (Blueprint $table) {
                if (Schema::hasColumn('goal_histories', 'start_date')) {
                    $table->dropColumn('start_date');
                }
                if (Schema::hasColumn('goal_histories', 'end_date')) {
                    $table->dropColumn('end_date');
                }
            });
        }
    }
};
