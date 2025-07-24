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
    public function up()
    {
        try {
            // USERS TABLE
            if (Schema::hasTable('users')) {
                if (Schema::hasColumn('users', 'phone')) {
                    DB::statement("ALTER TABLE users MODIFY phone VARCHAR(20) DEFAULT NULL");
                }

                if (Schema::hasColumn('users', 'description_character_count')) {
                    DB::statement("ALTER TABLE users MODIFY description_character_count BIGINT DEFAULT NULL");
                }
            }

            // ITEMS TABLE
            if (Schema::hasTable('items')) {
                if (Schema::hasColumn('items', 'protein') && Schema::hasColumn('items', 'carbs') && Schema::hasColumn('items', 'fat')) {
                    DB::statement("ALTER TABLE items MODIFY protein DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY carbs DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY fat DECIMAL(7,2) DEFAULT NULL");
                }
                if (Schema::hasColumn('items', 'qty')) {
                    DB::statement("ALTER TABLE items MODIFY qty VARCHAR(50) DEFAULT NULL");
                }
            }

            // ITEMS_1 TABLE
            if (Schema::hasTable('items_1')) {
                if (Schema::hasColumn('items_1', 'protein') && Schema::hasColumn('items_1', 'carbs') && Schema::hasColumn('items_1', 'fat')) {
                    DB::statement("ALTER TABLE items_1 MODIFY protein DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY carbs DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY fat DECIMAL(7,2) DEFAULT NULL");
                }
                if (Schema::hasColumn('items_1', 'qty')) {
                    DB::statement("ALTER TABLE items_1 MODIFY qty VARCHAR(50) DEFAULT NULL");
                }
            }

            // ITEMS_2 TABLE
            if (Schema::hasTable('items_2')) {
                if (Schema::hasColumn('items_2', 'protein') && Schema::hasColumn('items_2', 'carbs') && Schema::hasColumn('items_2', 'fat')) {
                    DB::statement("ALTER TABLE items_2 MODIFY protein DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY carbs DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY fat DECIMAL(7,2) DEFAULT NULL");
                }
                if (Schema::hasColumn('items_2', 'qty')) {
                    DB::statement("ALTER TABLE items_2 MODIFY qty VARCHAR(50) DEFAULT NULL");
                }
            }

            // ITEMS_LOCAL TABLE
            if (Schema::hasTable('items_local')) {
                if (Schema::hasColumn('items_local', 'protein') && Schema::hasColumn('items_local', 'carbs') && Schema::hasColumn('items_local', 'fat')) {
                    DB::statement("ALTER TABLE items_local MODIFY protein DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY carbs DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY fat DECIMAL(7,2) DEFAULT NULL");
                }
                if (Schema::hasColumn('items_local', 'qty')) {
                    DB::statement("ALTER TABLE items_local MODIFY qty VARCHAR(50) DEFAULT NULL");
                }
            }

            // BLOGS TABLE
            if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'description')) {
                DB::statement("ALTER TABLE blogs MODIFY description TEXT DEFAULT NULL");
            }

            // COUPONS TABLE
            if (Schema::hasTable('coupons') && Schema::hasColumn('coupons', 'code')) {
                DB::statement("ALTER TABLE coupons MODIFY code VARCHAR(100) NOT NULL");
            }

            // PASSWORD_RESETS TABLE
            if (Schema::hasTable('password_resets') && Schema::hasColumn('password_resets', 'id')) {
                DB::statement("ALTER TABLE password_resets MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            }

            // Trackings
            if (Schema::hasTable('trackings') && Schema::hasColumn('trackings', 'ip')) {
                DB::statement("ALTER TABLE trackings MODIFY COLUMN ip VARCHAR(100)");
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }
};