<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('payments') && ! Schema::hasColumn('payments', 'coupon_code')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('coupon_code', 100)->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'coupon_code')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('coupon_code');
            });
        }
    } 
};
