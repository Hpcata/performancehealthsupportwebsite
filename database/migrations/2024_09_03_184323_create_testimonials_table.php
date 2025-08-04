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
        if (! Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('designation');
                $table->text('review');
                $table->unsignedBigInteger('testimonial_image')->index()->nullable();
                $table->timestamps();

                $table->foreign('testimonial_image')
                    ->references('id')
                    ->on('media')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('testimonials')) {
            // Dynamically find and drop the foreign key constraint (if exists)
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'testimonials'
                  AND COLUMN_NAME = 'testimonial_image'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if (! empty($foreignKeys)) {
                $foreignKeyName = $foreignKeys[0]->CONSTRAINT_NAME;

                Schema::table('testimonials', function (Blueprint $table) use ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                });
            }

            Schema::dropIfExists('testimonials');
        }
    }
};