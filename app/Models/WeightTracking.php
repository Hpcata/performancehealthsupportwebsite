<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightTracking extends Model
{
    use HasFactory;

    protected $table = 'weight_trackings';

    protected $fillable = [
       'user_id',
       'date',
       'weight',
       'weight_goal'
    ];

}
