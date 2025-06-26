<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubCategory extends Model
{
    use HasFactory;

    protected $table = 'user_sub_categories';

    protected $fillable = [
        'user_plan_id',
        'user_category_id',
        'id'  // This will store sub_category_id
    ];

    public function userCategory()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }

    public function subCategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_sub_category_id');
    }
}

