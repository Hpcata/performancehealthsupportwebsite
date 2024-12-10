<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Subset;

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
        return $this->belongsToMany(SubCategory::class, 'category_subcategory');
    }
}
