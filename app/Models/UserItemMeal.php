<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItemMeal extends Model
{
    use HasFactory;

    protected $fillable = ['useer_id','item_id', 'meal_id'];

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'user_item_meals');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'user_item_meals', 'item_id');
    }

    public function userswapItems()
    {
        return $this->belongsToMany(Item::class, 'user_item_swaps', 'swap_item_id', 'item_id')
        ->wherePivot('item_id', '<>', \DB::raw('swap_item_id'));
    }

    public static function getUniqueUserIds()
    {
        return self::distinct('user_id')->pluck('user_id');
    }
}
