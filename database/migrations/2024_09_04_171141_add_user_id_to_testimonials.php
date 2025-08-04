<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('testimonials') && ! Schema::hasColumn('testimonials', 'user_id')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('testimonials') && Schema::hasColumn('testimonials', 'user_id')) {
            // Drop the foreign key constraint dynamically
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'testimonials'
                  AND COLUMN_NAME = 'user_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if (! empty($foreignKeys)) {
                $foreignKeyName = $foreignKeys[0]->CONSTRAINT_NAME;

                Schema::table('testimonials', function (Blueprint $table) use ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                });
            }

            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};