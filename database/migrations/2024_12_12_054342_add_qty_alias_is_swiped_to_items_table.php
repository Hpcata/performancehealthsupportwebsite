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
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (! Schema::hasColumn('items', 'qty')) {
                    $table->integer('qty')->default(0);
                }
                if (! Schema::hasColumn('items', 'alias')) {
                    $table->string('alias')->nullable();
                }
                if (! Schema::hasColumn('items', 'is_swiped')) {
                    $table->boolean('is_swiped')->default(false);
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
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (Schema::hasColumn('items', 'qty')) {
                    $table->dropColumn('qty');
                }
                if (Schema::hasColumn('items', 'alias')) {
                    $table->dropColumn('alias');
                }
                if (Schema::hasColumn('items', 'is_swiped')) {
                    $table->dropColumn('is_swiped');
                }
            });
        }
    }
};