<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    protected $table = 'user_plans';

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'modified_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function userMealTimes()
    {
        return $this->hasMany(UserMealTime::class, 'user_plan_id');
    }

    public function userCategories()
    {
        return $this->hasMany(UserCategory::class, 'user_plan_id');
    }

    public function userSubCategories()
    {
        return $this->hasMany(UserSubCategory::class, 'user_plan_id');
    }


    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_plan_id');
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'user_plan_id');
    }
}
