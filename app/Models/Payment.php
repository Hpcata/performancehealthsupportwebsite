<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define the table name if it is different from the model name
    protected $table = 'payments';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'plan_id',
        'user_id',
        'price',
        'name',
        'email',
        'phone',
        'payment_intent_id',
        'status',
    ];

    // Optionally, you can define relationships if needed
    // For example, if you have a Plan model and want to associate payments with plans:
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
