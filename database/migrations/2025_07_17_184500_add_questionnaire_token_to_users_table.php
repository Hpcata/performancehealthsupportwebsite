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
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'questionnaire_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('questionnaire_token')->nullable()->unique();
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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'questionnaire_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('questionnaire_token');
            });
        }
    }
};
