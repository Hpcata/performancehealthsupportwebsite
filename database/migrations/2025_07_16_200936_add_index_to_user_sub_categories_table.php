<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_sub_categories', function (Blueprint $table) {
            $table->index('user_category_id', 'user_sub_categories_user_category_id_index');
        });
    }

    public function down()
    {
        Schema::table('user_sub_categories', function (Blueprint $table) {
            $table->dropIndex('user_sub_categories_user_category_id_index');
        });
    }
};
