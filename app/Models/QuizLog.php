<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'user_id',
        'email',
        'completed_steps',
        'free_quiz_clicks',
        'completed_without_email',
        'completed_with_email'
    ];

    protected $casts = [
        'completed_steps' => 'array',
        'completed_without_email' => 'boolean',
        'completed_with_email' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 