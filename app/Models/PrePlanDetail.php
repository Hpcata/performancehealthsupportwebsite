<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePlanDetail extends Model
{
    use HasFactory;

    protected $table = 'pre_plan_details';

    protected $fillable = [
        'user_pre_plan_id',
        'form_name',
        'form_slug',
        'question',
        'answer',
    ];

    // Relationship with the UserPrePlan model (belongsTo)
    public function userPrePlan()
    {
        return $this->belongsTo(UserPrePlan::class);
    }

}
