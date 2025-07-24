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
        if (! Schema::hasTable('media_organization')) {
            Schema::create('media_organization', function (Blueprint $table) {
                $table->unsignedBigInteger('media_id');
                $table->unsignedBigInteger('user_id');
                $table->integer('sort_order')->default(0);
                $table->enum('position', ['top', 'bottom'])->default('top');

                $table->foreign('media_id')
                    ->references('id')
                    ->on('media')
                    ->onDelete('cascade');

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
        if (Schema::hasTable('media_organization')) {
            // Find and drop both foreign keys dynamically
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'media_organization'
                  AND COLUMN_NAME IN ('media_id', 'user_id')
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                Schema::table('media_organization', function (Blueprint $table) use ($fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                });
            }

            Schema::dropIfExists('media_organization');
        }
    }
};