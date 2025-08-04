<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOtpTokensColumnSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('otp_tokens')) {
            Schema::table('otp_tokens', function (Blueprint $table) {
                // $table->string('otp', 10)->change(); // Update column size from 6 to 10
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
        if (Schema::hasTable('otp_tokens')) {
            Schema::table('otp_tokens', function (Blueprint $table) {
                // $table->string('otp', 6)->change(); // Revert back to 6
            });
        }
    }
} 