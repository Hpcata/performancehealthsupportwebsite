<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create index on site_settings(page_id, meta_key) if it doesn't exist
        if (!$this->indexExists('site_settings', 'idx_page_id_meta_key')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->index(['page_id', 'meta_key'], 'idx_page_id_meta_key');
            });
        }

        // Create index on plan_sub_plans(sub_plan_id) if it doesn't exist
        if (!$this->indexExists('plan_sub_plans', 'idx_sub_plan_id')) {
            Schema::table('plan_sub_plans', function (Blueprint $table) {
                $table->index('sub_plan_id', 'idx_sub_plan_id');
            });
        }

        // Create index on blogs(is_published) if it doesn't exist
        if (!$this->indexExists('blogs', 'idx_is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('is_published', 'idx_is_published');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop index on site_settings(page_id, meta_key) if it exists
        if ($this->indexExists('site_settings', 'idx_page_id_meta_key')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropIndex('idx_page_id_meta_key');
            });
        }

        // Drop index on plan_sub_plans(sub_plan_id) if it exists
        if ($this->indexExists('plan_sub_plans', 'idx_sub_plan_id')) {
            Schema::table('plan_sub_plans', function (Blueprint $table) {
                $table->dropIndex('idx_sub_plan_id');
            });
        }

        // Drop index on blogs(is_published) if it exists
        if ($this->indexExists('blogs', 'idx_is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex('idx_is_published');
            });
        }
    }

    /**
     * Check if an index exists on a table.
     *
     * @param string $table
     * @param string $indexName
     * @return bool
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEXES FROM {$table} WHERE Key_name = ?", [$indexName]);
        return !empty($indexes);
    }
};