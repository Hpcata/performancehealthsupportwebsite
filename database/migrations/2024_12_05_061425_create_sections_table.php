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
        if (! Schema::hasTable('sections')) {
            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
                $table->string('title')->nullable();       // Optional title
                $table->string('type');                    // e.g., header, section-1, etc.
                $table->text('content')->nullable();       // JSON structure for flexibility
                $table->boolean('enabled')->default(true); // Visibility toggle
                $table->integer('order')->default(0);      // Sort order
                $table->string('image')->nullable();       // Optional image path
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sections')) {
            Schema::dropIfExists('sections');
        }
    }
};