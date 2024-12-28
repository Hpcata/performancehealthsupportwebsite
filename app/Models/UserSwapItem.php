<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSwapItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_plan_id',
        'user_meal_time_id',
        'user_category_id',
        'user_subcategory_id',
        'user_meal_id',
        'user_item_id',
        'swap_item_id',
    ];

    public function userMeal()
    {
        return $this->belongsTo(UserMeal::class, 'user_meal_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'swap_item_id');
    }

    public function userItem()
    {
        return $this->belongsTo(UserItem::class, 'user_item_id');
    }
    
}
