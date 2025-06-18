<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePlanQuesionFile extends Model
{
    use HasFactory;

    protected $table = 'pre_plan_question_files';

    protected $fillable = [
        'user_pre_plan_id',
        'form_slug',
        'question',
        'file_path',
        'file_name'
    ];
}
