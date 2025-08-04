<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_number');
            $table->string('otp', 10); // Increased from 6 to 10 to accommodate 'VERIFIED' status
            $table->boolean('is_verified')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['mobile_number', 'otp']);
            $table->index(['mobile_number', 'is_verified']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_tokens');
    }
} 