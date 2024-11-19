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
        Schema::create('inquiry_message', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id'); // Foreign key to queries.id
            $table->text('message');
            $table->enum('status', ['archive', 'draft', 'completed'])->default('draft');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_message');
    }
};
