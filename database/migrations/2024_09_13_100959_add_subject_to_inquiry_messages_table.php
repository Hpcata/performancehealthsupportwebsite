<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inquiry_message', function (Blueprint $table) {
            $table->string('subject')->after('user_id'); // Adjust 'after' to place it where you need in the table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry_message', function (Blueprint $table) {
            $table->dropColumn('subject');
        });
    }
};
