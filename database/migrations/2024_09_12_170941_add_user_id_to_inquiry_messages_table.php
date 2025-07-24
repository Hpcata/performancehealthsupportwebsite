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
        if (Schema::hasTable('inquiry_message') && ! Schema::hasColumn('inquiry_message', 'user_id')) {
            Schema::table('inquiry_message', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('query_id');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inquiry_message') && Schema::hasColumn('inquiry_message', 'user_id')) {
            // Dynamically drop foreign key if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'inquiry_message'
                  AND COLUMN_NAME = 'user_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if (! empty($foreignKeys)) {
                $fkName = $foreignKeys[0]->CONSTRAINT_NAME;

                Schema::table('inquiry_message', function (Blueprint $table) use ($fkName) {
                    $table->dropForeign($fkName);
                });
            }

            // Drop the column
            Schema::table('inquiry_message', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};