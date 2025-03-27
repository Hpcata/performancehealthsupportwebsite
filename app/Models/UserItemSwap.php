<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItemSwap extends Model
{
    use HasFactory;

    protected $table = 'user_item_swaps';

    protected $fillable = [
        'user_id',
        'item_id',
        'swap_item_id',
        'qty',
        'carbs',
        'protein',
        'fat',
        'unit'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function swapItem()
    {
        return $this->belongsTo(Item::class, 'swap_item_id');
    }
}
