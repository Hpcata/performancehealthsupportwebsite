<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    // Specify the table name (optional if it matches the model name in plural form)
    protected $table = 'questionnaire';

    // Specify the fillable fields to prevent mass assignment vulnerability
    protected $fillable = ['user_id', 'name', 'email', 'phone', 'question', 'answer'];
}
