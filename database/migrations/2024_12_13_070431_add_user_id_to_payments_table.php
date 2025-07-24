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
        if (Schema::hasTable('payments') && ! Schema::hasColumn('payments', 'user_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'user_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};