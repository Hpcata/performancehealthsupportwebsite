<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            // Drop foreign key if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'categories'
                  AND COLUMN_NAME = 'plan_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME = 'plans'
            ");

            if (!empty($foreignKeys)) {
                Schema::table('categories', function (Blueprint $table) use ($foreignKeys) {
                    $table->dropForeign($foreignKeys[0]->CONSTRAINT_NAME);
                });
            }

            // Drop the column
            if (Schema::hasColumn('categories', 'plan_id')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn('plan_id');
                });
            }

            // Add new 'order' column
            if (!Schema::hasColumn('categories', 'order')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->integer('order')->nullable()->after('image');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            // Remove 'order' column
            if (Schema::hasColumn('categories', 'order')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn('order');
                });
            }

            // Re-add 'plan_id' column and foreign key
            if (!Schema::hasColumn('categories', 'plan_id')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->unsignedBigInteger('plan_id')->after('id')->comment('fk : plans.id');

                    $table->foreign('plan_id')
                        ->references('id')
                        ->on('plans')
                        ->onDelete('cascade');
                });
            }
        }
    }
};
