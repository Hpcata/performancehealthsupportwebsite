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
        'user_category_id',
        'user_sub_category_id',
        'id'  // This will store meal_id
    ];

    public function userSubCategory()
    {
        return $this->belongsTo(UserSubCategory::class, 'user_sub_category_id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class, 'id');
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'user_meal_id');
    }
}
