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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'front_title')) {
                    $table->string('front_title')->nullable()->after('front_logo');
                }
                if (! Schema::hasColumn('users', 'front_description')) {
                    $table->longText('front_description')->nullable()->after('front_title');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'front_title')) {
                    $table->dropColumn('front_title');
                }
                if (Schema::hasColumn('users', 'front_description')) {
                    $table->dropColumn('front_description');
                }
            });
        }
    }
};