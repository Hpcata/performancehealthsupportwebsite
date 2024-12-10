<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image'];

    protected $table = 'meals';

    // Define relationship with MealTime
    public function mealTimes()
    {
        return $this->belongsToMany(MealTime::class, 'meal_meal_time');
    }

    // Define the relationship with SubCategory
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'meal_subcategory'); // Assuming a pivot table
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_meals');
    }
}
