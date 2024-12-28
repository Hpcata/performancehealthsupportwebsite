<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMealTime extends Model
{
    use HasFactory;

    // You can define any additional fields or methods here if needed
    protected $table = 'user_meal_times';

    protected $fillable = [
        'user_plan_id',
        'meal_time_id',
    ];

    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class, 'user_plan_id');
    }

    public function mealTime()
    {
        return $this->belongsTo(MealTime::class, 'meal_time_id');
    }

    public function userCategories()
    {
        return $this->hasMany(UserCategory::class, 'meal_time_id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_meal_time_id');
    }
}
