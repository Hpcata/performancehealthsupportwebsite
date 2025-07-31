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
