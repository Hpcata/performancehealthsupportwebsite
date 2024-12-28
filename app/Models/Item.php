<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'image', 'qty', 'alias', 'is_swiped'];

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'item_meals', 'item_id', 'meal_id');
    }

    public function swapItems()
    {
        return $this->belongsToMany(Item::class, 'item_swaps', 'item_id', 'swap_item_id')
        ->wherePivot('item_id', '<>', \DB::raw('swap_item_id'));
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_swaps', 'swap_item_id', 'item_id')
        ->wherePivot('item_id', '<>', \DB::raw('swap_item_id'));
    }

    public function userItemSwaps()
    {
        return $this->belongsToMany(Item::class, 'user_item_swaps', 'item_id', 'swap_item_id')
        ->wherePivot('item_id', '<>', \DB::raw('swap_item_id'));
    }
}
