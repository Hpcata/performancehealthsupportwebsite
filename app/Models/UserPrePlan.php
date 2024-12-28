<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrePlan extends Model
{
    use HasFactory;

    protected $table = 'user_pre_plans';

    protected $fillable = [
        'payment_id',
        'user_id',
        'dob',
        'occupation',
        'address',
        'other',
        
    ];

   // Relationship with the User model (belongsTo)
   public function user()
   {
       return $this->belongsTo(User::class);
   }

   // Relationship with the Payment model (belongsTo)
   public function payment()
   {
       return $this->belongsTo(Payment::class);
   }

   // Relationship with the PrePlanDetail model (hasMany)
   public function prePlanDetails()
   {
       return $this->hasMany(PrePlanDetail::class);
   }

}
