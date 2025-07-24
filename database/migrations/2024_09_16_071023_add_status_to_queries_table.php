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
        if (Schema::hasTable('queries') && ! Schema::hasColumn('queries', 'status')) {
            Schema::table('queries', function (Blueprint $table) {
                $table->enum('status', ['archive', 'completed'])
                    ->default('completed')
                    ->after('message');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('queries') && Schema::hasColumn('queries', 'status')) {
            Schema::table('queries', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};