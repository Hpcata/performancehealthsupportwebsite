<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Add any attributes you want to hide from serialization
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Add any attributes you want to cast 
        // 'created_at' => 'datetime',
        // 'updated_at' => 'datetime',
    ];

    public const QUIZ_BUTTON_CLICK        = 'quiz_button_click';
    public const QUIZ_STARTED             = 'quiz_started';
    public const QUIZ_QUESTION_ANSWER     = 'quiz_question_answer';
    public const QUIZ_COMPLETED           = 'quiz_completed';
    public const QUESTIONNAIRE_STARTED    = 'questionnaire_started';
    public const QUESTIONNAIRE_COMPLETED  = 'questionnaire_completed';
    public const ACCOUNT_CREATED          = 'account_created';
    public const PLAN_VIEWED              = 'plan_viewed';
    public const PRODUCT_SWAP             = 'product_swap';
    public const PROFILE_DETAILS_EDIT     = 'profile_details_edit';
    public const PLAN_SUBSCRIBED          = 'plan_subscribed';
    public const PLAN_EMAILED             = 'plan_emailed';
    public const COUPON_APPLIED           = 'coupon_applied';
    public const FREE_PLAN_COUPON         = 'free_plan_coupon';
    public const USER_PROFILE_ACTIVITY    = 'user_profile_activity';
    public const USER_LOGGED_IN           = 'user_logged_in';
    public const USER_LOGGED_OUT          = 'user_logged_out';

}
