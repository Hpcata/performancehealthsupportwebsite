<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubcategory extends Model
{
    use HasFactory;

    protected $table = 'user_subcategories';

    protected $fillable = [
        'user_plan_id',
        'user_meal_time_id',
        'user_category_id',
        'sub_category_id',
    ];

    public function userCategory()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_subcategory_id');
    }

}

