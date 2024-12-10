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
}
