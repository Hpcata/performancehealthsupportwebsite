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
        if (Schema::hasTable('items') && ! Schema::hasColumn('items', 'fat')) {
            Schema::table('items', function (Blueprint $table) {
                $table->decimal('fat', 5, 2)->nullable()->after('carbs');
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
        if (Schema::hasTable('items') && Schema::hasColumn('items', 'fat')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('fat');
            });
        }
    }
};