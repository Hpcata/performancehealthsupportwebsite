<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(SportGame::class, 'category_sport_game')
                    ->using(CategorySportGame::class)
                    ->withPivot('image_path')
                    ->withTimestamps();
    }

}
