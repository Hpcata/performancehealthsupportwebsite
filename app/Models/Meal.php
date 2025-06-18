<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'note', 'image', 'user_id'];

    protected $table = 'meals';

    // Define relationship with MealTime
    public function mealTimes()
    {
        return $this->belongsToMany(MealTime::class, 'meal_meal_time');
    }

    // Define the relationship with SubCategory
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'meal_category'); // Assuming a pivot table
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_meals', 'meal_id', 'item_id')
                    ->withPivot(['item_qty', 'item_qty_unit', 'carbs', 'protein', 'fat', 'selected_qty_unit']);
                    // ->where('is_swiped', 0);
    }

    // Many-to-many relationship with Item through the user_items pivot table
    public function userItems()
    {
        return $this->belongsToMany(Item::class, 'user_items', 'item_id', 'user_meal_id');
    }

    public function userMealItems()
    {
        return $this->belongsToMany(Item::class, 'user_item_meals', 'meal_id', 'item_id')
                    ->withPivot(['qty', 'unit', 'selected_qty_unit', 'user_id'])
                    ->wherePivot('is_swiped',0);
    }

    public function totalProtein()
    {
        return $this->items()->sum('protein');
    }

    public function totalCarbs()
    {
        return $this->items()->sum('carbs');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class); // Uses 'item_tag' pivot table by default
    }
}
