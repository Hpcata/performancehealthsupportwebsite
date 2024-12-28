<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTime extends Model
{   
    use HasFactory;

    protected $table = 'meal_times';

    protected $fillable = [
        'title',
        'time',
        'description',
        'image',
    ];

    /**
     * Define many-to-many relationship with Meal.
     */
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_meal_time');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_mealtime');
    }

    // MealTime has many users through the user_meal_times pivot table
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_meal_times');
    }

    // Many-to-many relationship with Category through the user_categories pivot table
    public function userCategories()
    {
        return $this->belongsToMany(Category::class, 'user_categories', 'meal_time_id', 'category_id');
    }
}
