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
        if (Schema::hasTable('inquiry_message') && ! Schema::hasColumn('inquiry_message', 'subject')) {
            Schema::table('inquiry_message', function (Blueprint $table) {
                $table->string('subject')->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inquiry_message') && Schema::hasColumn('inquiry_message', 'subject')) {
            Schema::table('inquiry_message', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }
    }
};