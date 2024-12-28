<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;

    protected $table = 'user_categories';

    protected $fillable = [
        'user_plan_id',
        'meal_time_id',
        'category_id',
    ];

    public function userMealTime()
    {
        return $this->belongsTo(UserMealTime::class, 'meal_time_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function userSubcategories()
    {
        return $this->hasMany(UserSubcategory::class, 'user_category_id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_category_id');
    }
}
