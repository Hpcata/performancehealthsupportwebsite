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
        // Use DB::statement to execute each raw SQL query

        try {
            // Update users.phone from INT to VARCHAR(20)
            \DB::statement("ALTER TABLE `users` MODIFY `phone` VARCHAR(20) DEFAULT NULL");

            // Update items.protein, carbs, fat from DECIMAL(5,2) to DECIMAL(7,2)
            \DB::statement("ALTER TABLE `items` MODIFY `protein` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `carbs` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `fat` DECIMAL(7,2) DEFAULT NULL");

            // Update items_1.protein, carbs, fat from DECIMAL(5,2) to DECIMAL(7,2)
            \DB::statement("ALTER TABLE `items_1` MODIFY `protein` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `carbs` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `fat` DECIMAL(7,2) DEFAULT NULL");

            // Update items_2.protein, carbs, fat from DECIMAL(5,2) to DECIMAL(7,2)
            \DB::statement("ALTER TABLE `items_2` MODIFY `protein` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `carbs` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `fat` DECIMAL(7,2) DEFAULT NULL");

            // Update items_local.protein, carbs, fat from DECIMAL(5,2) to DECIMAL(7,2)
            \DB::statement("ALTER TABLE `items_local` MODIFY `protein` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `carbs` DECIMAL(7,2) NOT NULL DEFAULT '0.00', MODIFY `fat` DECIMAL(7,2) DEFAULT NULL");

            // Update items.qty from TEXT to VARCHAR(50)
            \DB::statement("ALTER TABLE `items` MODIFY `qty` VARCHAR(50) DEFAULT NULL");

            // Update items_1.qty from TEXT to VARCHAR(50)
            \DB::statement("ALTER TABLE `items_1` MODIFY `qty` VARCHAR(50) DEFAULT NULL");

            // Update items_2.qty from TEXT to VARCHAR(50)
            \DB::statement("ALTER TABLE `items_2` MODIFY `qty` VARCHAR(50) DEFAULT NULL");

            // Update items_local.qty from TEXT to VARCHAR(50)
            \DB::statement("ALTER TABLE `items_local` MODIFY `qty` VARCHAR(50) DEFAULT NULL");

            // Update users.description_character_count from INT to BIGINT
            \DB::statement("ALTER TABLE `users` MODIFY `description_character_count` BIGINT DEFAULT NULL");

            // Update blogs.description from VARCHAR(510) to TEXT
            \DB::statement("ALTER TABLE `blogs` MODIFY `description` TEXT DEFAULT NULL");

            // Update coupons.code from VARCHAR(255) to VARCHAR(100)
            \DB::statement("ALTER TABLE `coupons` MODIFY `code` VARCHAR(100) NOT NULL");

            // Update password_resets.id from INT to BIGINT UNSIGNED
            \DB::statement("ALTER TABLE `password_resets` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
