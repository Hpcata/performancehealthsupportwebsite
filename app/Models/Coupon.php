<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code', 'type', 'value', 'min_order_value','start_date', 'end_date', 'max_uses', 'uses_per_user', 'status', 'description','usage_count'
    ];

    public const TYPE_PERCENT = 'percentage';
    public const TYPE_FIXED = 'fixed';

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'coupon_plans');
    }
}
