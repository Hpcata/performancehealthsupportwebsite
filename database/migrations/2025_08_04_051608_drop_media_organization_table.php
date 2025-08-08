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
        if (Schema::hasTable('media_organization')) {
            Schema::drop('media_organization');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('media_organization')) {
            Schema::create('media_organization', function (Blueprint $table) {
                $table->unsignedBigInteger('media_id');
                $table->unsignedBigInteger('user_id');
                $table->integer('sort_order')->default(0);
                $table->enum('position', ['top', 'bottom'])->default('top');

                $table->foreign('media_id')
                    ->references('id')->on('media')
                    ->onDelete('cascade');

                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            });
        }
    }
};
