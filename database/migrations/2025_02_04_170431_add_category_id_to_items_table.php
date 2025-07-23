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
        if (! Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable();

                // Add foreign key only if the table and referenced column exist
                if (Schema::hasTable('food_categories')) {
                    $table->foreign('category_id')
                        ->references('id')
                        ->on('food_categories')
                        ->onDelete('cascade');
                }
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
        if (Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', function (Blueprint $table) {
                // First drop the foreign key constraint if it exists
                $sm          = Schema::getConnection()->getDoctrineSchemaManager();
                $foreignKeys = $sm->listTableForeignKeys('items');

                foreach ($foreignKeys as $fk) {
                    if ($fk->getLocalColumns() === ['category_id']) {
                        $table->dropForeign(['category_id']);
                        break;
                    }
                }

                $table->dropColumn('category_id');
            });
        }
    }
};