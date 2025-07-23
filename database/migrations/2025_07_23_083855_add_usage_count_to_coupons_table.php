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
        if (Schema::hasTable('coupons') && !Schema::hasColumn('coupons', 'usage_count')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->integer('usage_count', 4)->default(0)->after('uses_per_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('coupons') && Schema::hasColumn('coupons', 'usage_count')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('usage_count');
            });
        }
    }
};
