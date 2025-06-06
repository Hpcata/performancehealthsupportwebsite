<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_categories', function (Blueprint $table) {
            // Drop the category_id column
            $table->dropColumn('category_id');
            
            // Modify the id column to be auto-increment
            $table->id()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_categories', function (Blueprint $table) {
            // Add back the category_id column
            $table->unsignedBigInteger('category_id')->after('id');
            
            // Remove auto-increment from id
            $table->unsignedBigInteger('id')->change();
        });
    }
} 