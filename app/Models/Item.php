<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'image', 'qty', 'unit', 'is_swiped', 'protein', 'carbs', 'fat', 'energy', 'saturated', 'sugars', 'dietary_fibre', 'sodium', 'category_id', 'serving_per_pack', 'serving_size', 'serving_size_unit', 'selected_qty_unit','is_locked', 'note', 'woolworth_json'];

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class); // Uses 'item_tag' pivot table by default
    }

    public function flags()
    {
        return $this->belongsToMany(Flag::class, 'flag_item');
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'id', 'id');
    }

    public function isDeletable(): bool
    {
        return !(
            $this->userItems()->exists() ||
            $this->meals()->exists() ||
            $this->swapItems()->exists() ||
            $this->items()->exists() ||
            $this->userItemSwaps()->exists() ||
            $this->tags()->exists() ||
            $this->flags()->exists()
        );
    }
}
