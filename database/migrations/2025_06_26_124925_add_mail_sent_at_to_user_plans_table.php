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
        if (Schema::hasTable('user_plans') && ! Schema::hasColumn('user_plans', 'mail_sent_at')) {
            Schema::table('user_plans', function (Blueprint $table) {
                $table->timestamp('mail_sent_at')->nullable()->after('is_mail_sent');
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
        if (Schema::hasTable('user_plans') && Schema::hasColumn('user_plans', 'mail_sent_at')) {
            Schema::table('user_plans', function (Blueprint $table) {
                $table->dropColumn('mail_sent_at');
            });
        }
    }
};