<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tags') && !Schema::hasColumn('tags', 'icon')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->string('icon')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tags') && Schema::hasColumn('tags', 'icon')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }
};
