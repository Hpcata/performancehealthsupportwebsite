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
        if (! Schema::hasTable('inquiry_message')) {
            Schema::create('inquiry_message', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('query_id');
                $table->text('message');
                $table->enum('status', ['archive', 'draft', 'completed'])->default('draft');
                $table->timestamps();

                $table->foreign('query_id')
                    ->references('id')
                    ->on('queries')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inquiry_message')) {
            // Dynamically drop the foreign key if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'inquiry_message'
                  AND COLUMN_NAME = 'query_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if (! empty($foreignKeys)) {
                $fkName = $foreignKeys[0]->CONSTRAINT_NAME;

                Schema::table('inquiry_message', function (Blueprint $table) use ($fkName) {
                    $table->dropForeign($fkName);
                });
            }

            Schema::dropIfExists('inquiry_message');
        }
    }
};