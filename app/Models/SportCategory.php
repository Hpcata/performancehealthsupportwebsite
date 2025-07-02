<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function games() {
        return $this->hasMany(SportGame::class, 'sport_category_id');
    }

}
