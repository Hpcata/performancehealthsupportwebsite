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
        // Only create if the table doesn't already exist
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id')->comment('fk : plans.id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();

                $table->foreign('plan_id')
                    ->references('id')
                    ->on('plans')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            // Drop FK constraint if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'categories'
                  AND COLUMN_NAME = 'plan_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME = 'plans'
            ");

            if (! empty($foreignKeys)) {
                Schema::table('categories', function (Blueprint $table) use ($foreignKeys) {
                    $table->dropForeign($foreignKeys[0]->CONSTRAINT_NAME);
                });
            }

            Schema::dropIfExists('categories');
        }
    }
};
