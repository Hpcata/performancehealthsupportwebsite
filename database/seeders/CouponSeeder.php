<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 20x1: Single coupon code reusable 20 times
        DB::table('coupons')->insert([
            'code' => 'Athlete20',
            'description' => 'Reusable discount code for athletes',
            'type' => 'percentage',
            'value' => 100.00,
            'min_order_value' => Null, // Optional: Set a minimum order value
            'start_date' => $now,
            'end_date' => $now->copy()->addMonth(),
            'max_uses' => 20,
            'uses_per_user' => 0, // 0 means unlimited per user
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 20 individual unique coupon codes
        $individualCoupons = [];
        for ($i = 1; $i <= 20; $i++) {
            $individualCoupons[] = [
                'code' => 'Athlete20-' . $i,
                'description' => 'Unique discount code for athlete #' . $i,
                'type' => 'percentage',
                'value' => 100.00,
                'min_order_value' => Null, // Optional: Set a minimum order value
                'start_date' => $now,
                'end_date' => $now->copy()->addMonth(),
                'max_uses' => 1, // Each code can only be used once
                'uses_per_user' => 1, // Each user can use it once
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('coupons')->insert($individualCoupons);
    }
}
