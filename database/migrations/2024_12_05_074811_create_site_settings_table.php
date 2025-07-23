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
        if (! Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id')->nullable()->index(); // Assuming it might relate to pages
                $table->string('meta_key')->index();                        // e.g., site_title, footer_text
                $table->text('meta_value')->nullable();                     // The setting value
                $table->integer('sort_order')->nullable();                  // Optional sorting
                $table->timestamps();

                // Optional: If linked to `pages` table
                // $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('site_settings')) {
            Schema::dropIfExists('site_settings');
        }
    }
};