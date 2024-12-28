<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Subset;
use App\Models\MealTime;
use App\Models\SubCategory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image'];

    public function mealtimes()
    {
        return $this->belongsToMany(MealTime::class, 'category_mealtime');
    }

    // Define the many-to-many relationship
    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'category_subcategory', 'category_id', 'sub_category_id');
    }

    // Category has many meal times
    public function userMealTimes()
    {
        return $this->belongsToMany(MealTime::class, 'user_meal_times');
    }

    // Many-to-many relationship with SubCategory through the user_subcategories pivot table
    // public function userSubcategories()
    // {
    //     return $this->hasMany(SubCategory::class);
    // }

    public function userSubcategories()
    {
        return $this->belongsToMany(SubCategory::class, 'user_subcategories', 'user_category_id', 'sub_category_id');
    }

    // Define the relationship with Meal
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_category'); // Assuming a pivot table
    }
}
