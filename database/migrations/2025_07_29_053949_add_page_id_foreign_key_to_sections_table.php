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
        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table) {
                // Add foreign key constraint to existing page_id column
                $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropForeign(['page_id']);
            });
        }
    }
};
