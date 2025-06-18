<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\FoodCategory;

class FoodCategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $categories = [
            ['name' => 'Poultry, Meat & Seafood', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Deli & Chilled Meals', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dairy, Eggs & Fridge', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bakery', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Freezer', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Snacks & Confectionery', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pantry', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'International Foods', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Drinks', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fruit & Vegetables', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Takeaway Foods', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insert multiple records using model's insert method
        FoodCategory::insert($categories);
    }
}
