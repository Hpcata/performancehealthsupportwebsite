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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->text('review');
            $table->unsignedBigInteger('testimonial_image')->index()->nullable();
            $table->timestamps();

            $table->foreign('testimonial_image')->references('id')->on('media')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign('testimonials_testimonial_image_foreign');
        });

        Schema::dropIfExists('testimonials');
    }
};
