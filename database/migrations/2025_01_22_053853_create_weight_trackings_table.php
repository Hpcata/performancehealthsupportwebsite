<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('weight_trackings')) {
            Schema::create('weight_trackings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->float('weight');
                $table->float('weight_goal')->nullable();
                $table->date('date');
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('weight_trackings')) {
            Schema::table('weight_trackings', function (Blueprint $table) {
                // Drop foreign key constraint before dropping table
                $table->dropForeign(['user_id']);
            });

            Schema::dropIfExists('weight_trackings');
        }
    }
};