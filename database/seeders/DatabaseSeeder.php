<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CouponSeeder;
use Database\Seeders\FoodCategorySeeder;
use Database\Seeders\TrackingTypesSeeder;
use Database\Seeders\CouponSourceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CouponSeeder::class);
        $this->call(FoodCategorySeeder::class);
        $this->call(TrackingTypesSeeder::class);
        $this->call(CouponSourceSeeder::class);
        $this->call(QuizQuestionSeeder::class);
    }
}
