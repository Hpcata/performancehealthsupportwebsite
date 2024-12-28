<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMeal extends Model
{
    use HasFactory;

    // Disable timestamps if they are not needed
    public $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'item_meals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['item_id', 'meal_id'];

    /**
     * Relationship to the Item model.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Relationship to the Meal model.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
}
