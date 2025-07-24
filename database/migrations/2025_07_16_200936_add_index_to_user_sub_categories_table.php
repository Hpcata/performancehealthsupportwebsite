<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $indexName = 'user_sub_categories_user_category_id_index';
    protected $tableName = 'user_sub_categories';

    public function up()
    {
        if (Schema::hasTable($this->tableName) && ! $this->indexExists()) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('user_category_id', $this->indexName);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable($this->tableName) && $this->indexExists()) {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropIndex($this->indexName);
            });
        }
    }

    /**
     * Check if index exists on the table.
     */
    private function indexExists(): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$this->tableName} WHERE Key_name = ?", [$this->indexName]);
        return ! empty($indexes);
    }
};