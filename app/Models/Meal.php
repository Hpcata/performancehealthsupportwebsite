<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'note', 'image', 'user_id'];

    protected $table = 'meals';

    // Define the relationship with Category
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'meal_category');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'meal_sub_category', 'meal_id', 'sub_category_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_meals')
            ->withPivot('item_qty', 'item_qty_unit', 'carbs', 'protein', 'fat', 'energy', 'selected_qty_unit')
            ->withTimestamps();
    }

    // Many-to-many relationship with Item through the user_items pivot table
    public function userItems()
    {
        return $this->belongsToMany(Item::class, 'user_items', 'item_id', 'user_meal_id');
    }

    public function userMealItems()
    {
        return $this->belongsToMany(Item::class, 'user_item_meals', 'meal_id', 'item_id')
                    ->withPivot(['qty', 'unit', 'selected_qty_unit', 'user_id', 'carbs', 'protein', 'fat', 'energy'])
                    ->wherePivot('is_swiped',0);
    }

    public function getTotalProteinsAttribute()
    {
        return $this->items()->sum('item_meals.protein');
    }

    public function getTotalCarbsAttribute()
    {
        return $this->items()->sum('item_meals.carbs');
    }

    public function getTotalFatsAttribute()
    {
        return $this->items()->sum('item_meals.fat');
    }

    public function getTotalEnergyAttribute()
    {
        return $this->items->sum(function ($item) {
            // Use regex to extract numeric part from "1200kJ", "783 kcal", etc.
            return floatval(preg_replace('/[^0-9.]/', '', $item->energy));
        });
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class); // Uses 'item_tag' pivot table by default
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'id');  // Changed from meal_id to id
    }

    public function isDeletable(): bool
    {
        return !(
            $this->items()->exists() ||
            $this->userItems()->exists() ||
            $this->userMealItems()->exists() ||
            $this->userMeals()->exists() ||
            $this->categories()->exists() ||
            $this->subCategories()->exists() ||
            $this->tags()->exists()
        );
    }

}
