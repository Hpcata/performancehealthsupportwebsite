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
        if (! Schema::hasTable('trackings')) {
            Schema::create('trackings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('type_id')->constrained('tracking_types');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('section_element_id')->nullable();
                $table->unsignedBigInteger('user_click_id')->nullable();
                $table->unsignedBigInteger('ip');
                $table->json('details')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('section_element_id')->references('id')->on('section_elements')->onDelete('set null');
                $table->foreign('user_click_id')->references('id')->on('user_clicks')->onDelete('set null');

                // Indexes
                $table->index(['type_id', 'created_at'], 'trackings_type_created_at_index');
                $table->index(['created_at'], 'trackings_created_at_index');
                $table->index(['ip'], 'trackings_ip_index');
                $table->index(['user_id'], 'trackings_user_index');
                $table->index(['type_id'], 'trackings_type_index');
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
        if (Schema::hasTable('trackings')) {
            Schema::dropIfExists('trackings');
        }
    }
};