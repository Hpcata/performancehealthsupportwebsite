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
        if (Schema::hasTable('sections') && Schema::hasColumn('sections', 'type')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('type');
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
        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->string('type')->nullable(); // or set as non-nullable if originally required
            });
        }
    }
};
