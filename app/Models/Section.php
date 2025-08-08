<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    // Section type constants
    const TYPE_MAIN_BANNER = 'main_banner';
    const TYPE_ABOUT_US = 'about_us';
    const TYPE_EAT_BETTER = 'eat_better';
    const TYPE_WHY_IT_WORKS = 'why_it_works';
    const TYPE_CHOOSE_YOUR_PLAN = 'choose_your_plan';
    const TYPE_FIND_YOUR_SPORT = 'find_your_sport';
    const TYPE_REAL_STORIES = 'real_stories';
    const TYPE_PARTNERS = 'partners';
    const TYPE_ABOUT_US_BANNER = 'about_us_banner';
    const TYPE_ATHLETE_NUTRITION_FOCUS = 'athlete_nutrition_focus';
    const TYPE_ABOUT_KERRY_INTRO = 'about_kerry_intro';
    const TYPE_ABOUT_BOOKING = 'about_booking';
    const TYPE_ATHLETES_WE_WORK_WITH = 'athletes_we_work_with';
    const TYPE_TRAINING_PLAN_MAIN_BANNER = 'training_plan_main_banner';
    const TYPE_BUILT_FOR_REAL_RESULT = 'built_for_real_result';
    const TYPE_PLAN_INCLUSIONS = 'plan_inclusions';
    const TYPE_PLAN_INTERESTS = 'plan_interests';
    const TYPE_COMPETITION_MAIN_BANNER = 'competition_main_banner';
    const TYPE_COMPETE_AT_YOUR_PEAK = 'compete_at_your_peak';
    const TYPE_COMPETITION_PLAN_INCLUSIONS = 'comepetition_plan_inclusions';
    const TYPE_COMPETITION_PLAN_INTERESTS = 'comepetition_plan_interests';
    const TYPE_INJURY_PLAN_MAIN_BANNER = 'injury_plan_banner';
    const TYPE_RECOVER_QUICKER = 'recover_quicker';
    const TYPE_INJURY_PLAN_INCLUSIONS = 'injury_plan_inclusions';
    const TYPE_INJURY_PLAN_INTERESTS = 'injury_plan_interests';

    // Get all available section types
    public static function getSectionTypes()
    {
        return [
            self::TYPE_MAIN_BANNER => 'Main Banner',
            self::TYPE_ABOUT_US => 'About Us',
            self::TYPE_EAT_BETTER => 'Eat Better',
            self::TYPE_WHY_IT_WORKS => 'Why It Works',
            self::TYPE_CHOOSE_YOUR_PLAN => 'Choose Your Plan',
            self::TYPE_FIND_YOUR_SPORT => 'Find Your Sport',
            self::TYPE_REAL_STORIES => 'Real Stories',
            self::TYPE_PARTNERS => 'Partners',
            self::TYPE_ABOUT_US_BANNER => 'About Us Banner',
            self::TYPE_ATHLETE_NUTRITION_FOCUS => 'Athlete Nutrition Focus',
            self::TYPE_ABOUT_KERRY_INTRO => 'About Kerry Intro',
            self::TYPE_ABOUT_BOOKING => 'About Booking',
            self::TYPE_ATHLETES_WE_WORK_WITH => 'Athletes We Work With',
            self::TYPE_TRAINING_PLAN_MAIN_BANNER => 'Training Plan Main Banner',
            self::TYPE_BUILT_FOR_REAL_RESULT => 'Built For Real Result',
            self::TYPE_PLAN_INCLUSIONS => 'Plan Inclusions',
            self::TYPE_PLAN_INTERESTS => 'Plan Interests',
            self::TYPE_COMPETITION_MAIN_BANNER => 'Competition Main Banner',
            self::TYPE_COMPETE_AT_YOUR_PEAK => 'Compete At Your Peak',
            self::TYPE_COMPETITION_PLAN_INCLUSIONS => 'Competition Plan Inclusions',
            self::TYPE_COMPETITION_PLAN_INTERESTS => 'Competition Plan Interests',
            self::TYPE_INJURY_PLAN_MAIN_BANNER => 'Injury Plan Banner',
            self::TYPE_RECOVER_QUICKER => 'Recover Quicker',
            self::TYPE_INJURY_PLAN_INCLUSIONS => 'Injury Plan Inclusions',
            self::TYPE_INJURY_PLAN_INTERESTS => 'Injury Plan Interests',
        ];
    }

    protected $fillable = ['title', 'section_type', 'page_id', 'enabled','content','order','image', 'banner_image'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'array',
        'banner_image' => 'array',
    ];

    /**
     * Define the relationship with the Page model.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get section by type
     */
    public static function findByType($type)
    {
        return self::where('section_type', $type)->first();
    }
}
