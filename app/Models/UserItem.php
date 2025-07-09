<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_plan_id',
        'user_category_id',
        'user_sub_category_id',
        'user_meal_id',
        'id'
    ];

    public function userMeal()
    {
        return $this->belongsTo(UserMeal::class, 'user_meal_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id');
    }

    /**
     * Relationship to get swap items for the current user item.
     */
    public function swapItems()
    {
        return $this->item->swapItems();
    }

    public function userSwapItems()
    {
        return $this->hasMany(UserSwapItem::class, 'user_item_id');
    }
}
