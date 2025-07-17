<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItemMeal extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','item_id', 'meal_id', 'qty', 'unit', 'carbs', 'protein', 'fat', 'energy', 'selected_qty_unit'];

    protected $casts = [
        'selected_qty_unit' => 'array',
    ];

    public function meals()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
        // return $this->belongsToMany(Meal::class, 'user_item_meals');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
        // return $this->belongsToMany(Item::class, 'user_item_meals', 'item_id');
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

    public function getItems($item_ids) {
        return Item::select([
            'id',
            'title',
            'unit',
            'carbs',
            'protein',
            'fat',
            'energy',
            'description',
            'selected_qty_unit'
        ])->whereIn('id', $item_ids)->get()->toArray();
    }
}
