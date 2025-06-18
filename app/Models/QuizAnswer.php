<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_id',
        'form_slug',
        'question',
        'question_index',
        'step',
        'options',
        'answer',
        'is_required'
    ];

    protected $casts = [
        'options' => 'array',
        'answer' => 'array',
        'is_required' => 'boolean'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
} 