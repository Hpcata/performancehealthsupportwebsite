<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'form_slug',
        'question_index',
        'question_text',
        'options',
        'correct_answer'
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array'
    ];
} 