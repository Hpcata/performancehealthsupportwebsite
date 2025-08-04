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
        if (! Schema::hasTable('user_clicks')) {
            Schema::create('user_clicks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_element_id')->constrained('section_elements')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('ip', 45)->nullable(); // IPv4 and IPv6 support
                $table->timestamp('clicked_at')->useCurrent();
                $table->timestamps();

                $table->index(['section_element_id', 'clicked_at'], 'user_clicks_section_element_clicked_at_index');
                $table->index(['user_id'], 'user_clicks_user_index');
                $table->index(['ip'], 'user_clicks_ip_index');
                $table->index(['clicked_at'], 'user_clicks_clicked_at_index');
                $table->index(['section_element_id'], 'user_clicks_section_element_index');
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
        if (Schema::hasTable('user_clicks')) {
            Schema::dropIfExists('user_clicks');
        }
    }
};