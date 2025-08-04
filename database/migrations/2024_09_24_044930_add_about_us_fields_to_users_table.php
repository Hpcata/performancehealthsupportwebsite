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
                if (! Schema::hasColumn('users', 'about_us_image')) {
                    $table->string('about_us_image')->nullable()->after('profile_image');
                }
                if (! Schema::hasColumn('users', 'front_logo')) {
                    $table->string('front_logo')->nullable()->after('about_us_image');
                }
                if (! Schema::hasColumn('users', 'about_us_title')) {
                    $table->string('about_us_title')->nullable()->after('front_logo');
                }
                if (! Schema::hasColumn('users', 'about_us_description')) {
                    $table->text('about_us_description')->nullable()->after('about_us_title');
                }
                if (! Schema::hasColumn('users', 'copyright_text')) {
                    $table->string('copyright_text')->nullable()->after('about_us_description');
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
                if (Schema::hasColumn('users', 'about_us_image')) {
                    $table->dropColumn('about_us_image');
                }
                if (Schema::hasColumn('users', 'front_logo')) {
                    $table->dropColumn('front_logo');
                }
                if (Schema::hasColumn('users', 'about_us_title')) {
                    $table->dropColumn('about_us_title');
                }
                if (Schema::hasColumn('users', 'about_us_description')) {
                    $table->dropColumn('about_us_description');
                }
                if (Schema::hasColumn('users', 'copyright_text')) {
                    $table->dropColumn('copyright_text');
                }
            });
        }
    }
};