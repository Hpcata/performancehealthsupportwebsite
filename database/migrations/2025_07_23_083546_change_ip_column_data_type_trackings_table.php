<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trackings') && Schema::hasColumn('trackings', 'ip')) {
            // Drop index if it exists
            DB::statement("DROP INDEX IF EXISTS trackings_ip_index ON trackings");

            // Modify column type using raw SQL (assumes `ip` was BIGINT and needs to become VARCHAR(45))
            DB::statement("ALTER TABLE trackings MODIFY ip VARCHAR(45)");

            // Re-add index
            Schema::table('trackings', function ($table) {
                $table->index('ip', 'trackings_ip_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trackings') && Schema::hasColumn('trackings', 'ip')) {
            Schema::table('trackings', function ($table) {
                $table->dropIndex('trackings_ip_index');
            });

            // Revert to original type (e.g., UNSIGNED BIGINT)
            DB::statement("ALTER TABLE trackings MODIFY ip BIGINT UNSIGNED");

            // Re-add index
            Schema::table('trackings', function ($table) {
                $table->index('ip', 'trackings_ip_index');
            });
        }
    }
};
