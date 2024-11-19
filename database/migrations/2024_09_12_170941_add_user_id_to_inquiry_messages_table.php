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
            $table->unsignedBigInteger('user_id')->nullable()->after('query_id'); // Add user_id column

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry_message', function (Blueprint $table) {
            // Drop the foreign key and the column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
