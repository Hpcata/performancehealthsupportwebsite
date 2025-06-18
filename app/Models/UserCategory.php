<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;

    protected $table = 'user_categories';

    protected $fillable = [
        'user_plan_id',
        'id'  // This will store category_id
    ];

    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class, 'user_plan_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id');
    }

    public function userSubCategories()
    {
        return $this->hasMany(UserSubCategory::class, 'user_category_id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_category_id');
    }
}
