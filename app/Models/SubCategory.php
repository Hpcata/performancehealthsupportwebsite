<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'id',
        'title',
        'description',
        'image',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'subcategory_category');
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_sub_category', 'sub_category_id', 'meal_id');
    }


    public function items()
    {
        return $this->belongsToMany(Item::class, 'subcategories_items');
    }
    
    // Many-to-many relationship with Meal through the user_meals pivot table
    public function userMeals()
    {
        return $this->belongsToMany(Meal::class, 'user_meals', 'user_sub_category_id', 'meal_id');
    }

    public function userSubCategories()
    {
        return $this->hasMany(UserSubCategory::class, 'id');
    }
}
