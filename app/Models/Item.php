<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'image', 'qty', 'unit', 'is_swiped', 'protein', 'carbs', 'fat', 'category_id', 'serving_per_pack', 'serving_size', 'serving_size_unit', 'selected_qty_unit','is_locked'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'selected_qty_unit' => 'array',
    ];
    
    public $timestamps = true;  // Ensure timestamps are enabled

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'item_meals', 'item_id', 'meal_id');
    }

    public function swapItems()
    {
        return $this->belongsToMany(Item::class, 'item_swaps', 'item_id', 'swap_item_id');
        // ->wherePivot('item_id', '<>', \DB::raw('swap_item_id'));
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

    public function category()
    {
        return $this->belongsTo(FoodCategory::class);
    }
}
