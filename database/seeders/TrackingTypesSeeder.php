<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrackingType;

class TrackingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'quiz_button_click',
            'quiz_started',
            'quiz_question_answer',
            'quiz_completed',
            'questionnaire_started',
            'questionnaire_completed',
            'account_created',
            'plan_viewed',
            'product_swap',
            'profile_details_edit',
            'plan_subscribed',
            'plan_emailed',
            'coupon_applied',
            'free_plan_coupon',
            'user_profile_activity',
            'user_logged_in',
            'user_logged_out',
        ];

        foreach ($types as $type) {
            TrackingType::firstOrCreate(['type' => $type]);
        }
    }
}
