<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image'];

    protected $table = 'subcategories';

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subcategory', 'sub_category_id', 'category_id');
    }

    // Define the relationship with Meal
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_subcategory'); // Assuming a pivot table
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'subcategories_items');
    }
    
    // Many-to-many relationship with Meal through the user_meals pivot table
    public function userMeals()
    {
        return $this->belongsToMany(Meal::class, 'user_meals', 'user_subcategory_id', 'meal_id');
    }
    
}
