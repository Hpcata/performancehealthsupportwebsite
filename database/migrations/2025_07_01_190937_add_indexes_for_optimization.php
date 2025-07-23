<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // site_settings: idx_page_id_meta_key
        if (! DB::select("SHOW INDEXES FROM site_settings WHERE Key_name = 'idx_page_id_meta_key'")) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->index(['page_id', 'meta_key'], 'idx_page_id_meta_key');
            });
        }

        // plan_sub_plans: idx_sub_plan_id
        if (! DB::select("SHOW INDEXES FROM plan_sub_plans WHERE Key_name = 'idx_sub_plan_id'")) {
            Schema::table('plan_sub_plans', function (Blueprint $table) {
                $table->index('sub_plan_id', 'idx_sub_plan_id');
            });
        }

        // blogs: idx_is_published
        if (! DB::select("SHOW INDEXES FROM blogs WHERE Key_name = 'idx_is_published'")) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('is_published', 'idx_is_published');
            });
        }
    }

    public function down(): void
    {
        // site_settings: idx_page_id_meta_key
        if (DB::select("SHOW INDEXES FROM site_settings WHERE Key_name = 'idx_page_id_meta_key'")) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropIndex('idx_page_id_meta_key');
            });
        }

        // plan_sub_plans: idx_sub_plan_id
        if (DB::select("SHOW INDEXES FROM plan_sub_plans WHERE Key_name = 'idx_sub_plan_id'")) {
            Schema::table('plan_sub_plans', function (Blueprint $table) {
                $table->dropIndex('idx_sub_plan_id');
            });
        }

        // blogs: idx_is_published
        if (DB::select("SHOW INDEXES FROM blogs WHERE Key_name = 'idx_is_published'")) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex('idx_is_published');
            });
        }
    }
};