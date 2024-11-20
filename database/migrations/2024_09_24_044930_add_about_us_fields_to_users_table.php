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
        Schema::table('users', function (Blueprint $table) {
            $table->string('about_us_image')->nullable()->after('profile_image');
            $table->string('front_logo')->nullable()->after('about_us_image');
            $table->string('about_us_title')->nullable()->after('front_logo');
            $table->text('about_us_description')->nullable()->after('about_us_title');
            $table->string('copyright_text')->nullable()->after('about_us_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('about_us_image');
            $table->dropColumn('front_logo');
            $table->dropColumn('about_us_title');
            $table->dropColumn('about_us_description');
            $table->dropColumn('copyright_text');
        });
    }
};
