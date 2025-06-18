<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalHistory extends Model
{
    use HasFactory;

    protected $table = 'goal_histories';

    protected $fillable = ['user_id', 'payment_id', 'type', 'question', 'answer', 'start_date', 'end_date'];

    protected $casts = [
        'answer' => 'array', // Ensure answer is handled as JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
