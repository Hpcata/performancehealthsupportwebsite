<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_sub_categories', function (Blueprint $table) {
            // First, copy sub_category_id values to id column
            DB::statement('UPDATE user_sub_categories SET id = sub_category_id');
            
            // Then drop the sub_category_id column
            $table->dropColumn('sub_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_sub_categories', function (Blueprint $table) {
            // Add back the sub_category_id column
            $table->unsignedBigInteger('sub_category_id')->after('user_category_id');
            
            // Copy id values back to sub_category_id
            DB::statement('UPDATE user_sub_categories SET sub_category_id = id');
        });
    }
}; 