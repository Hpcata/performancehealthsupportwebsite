<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportGame extends Model
{
    use HasFactory;

    
    protected $fillable = ['name', 'sport_category_id', 'image_path'];

    public function category() {
        return $this->belongsTo(SportCategory::class, 'sport_category_id');
    }

}
