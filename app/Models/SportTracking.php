<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport',
        'state',
        'sport_game',
        'ip',
    ];
}
