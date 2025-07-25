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
        if (! Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('description', 510)->nullable();
                $table->unsignedBigInteger('author')->nullable(); // FK to users.id
                $table->string('image')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamps();

                $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (Schema::hasColumn('blogs', 'author')) {
                    $table->dropForeign(['author']);
                }
            });

            Schema::dropIfExists('blogs');
        }
    }
};