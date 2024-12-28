<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'subtitle',
        'price',
        'description',
        'image',
    ];

    /**
     * Relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship with MealTime
    public function mealTimes()
    {
        return $this->belongsToMany(MealTime::class, 'plan_meal_time');
    }


    public function subPlans()
    {
        return $this->belongsToMany(Plan::class, 'plan_sub_plans', 'plan_id', 'sub_plan_id');
    }

    public function parentPlans()
    {
        return $this->belongsToMany(Plan::class, 'plan_sub_plans', 'sub_plan_id', 'plan_id');
    }
}
