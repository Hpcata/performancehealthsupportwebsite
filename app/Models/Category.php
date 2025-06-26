<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'description',
        'image',
        'order'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_category')
            ->orderBy('categories.order', 'asc');
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_category');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'subcategory_category');
    }

    public function userPlans()
    {
        return $this->hasMany(UserCategory::class);
    }

    // Many-to-many relationship with SubCategory through the user_subcategories pivot table
    // public function userSubcategories()
    // {
    //     return $this->hasMany(SubCategory::class);
    // }
}
