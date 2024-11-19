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
        Schema::create('media_organization', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('sort_order')->default(0);
            $table->enum('position', ['top', 'bottom'])->default('top');

            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_organization', function (Blueprint $table) {
            $table->dropForeign('media_organization_user_id_foreign');
            $table->dropForeign('media_organization_media_id_foreign');
        });
        Schema::dropIfExists('media_organization');
    }
};
