<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeal extends Model
{
    use HasFactory;

    protected $table = 'user_meals';

    protected $fillable = [
        'user_plan_id',
        'user_meal_time_id',
        'user_category_id',
        'user_subcategory_id',
        'meal_id',
    ];

    public function userSubcategory()
    {
        return $this->belongsTo(UserSubcategory::class, 'user_subcategory_id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'user_meal_id');
    }

    public function userCategory()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }
}
