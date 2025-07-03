<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategorySportGame extends Pivot
{
    protected $table = 'category_sport_game';
    protected $fillable = ['sport_category_id', 'sport_game_id', 'image_path'];
}