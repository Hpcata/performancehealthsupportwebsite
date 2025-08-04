<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_number',
        'otp',
        'is_verified',
        'expires_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Check if the OTP is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark OTP as verified
     *
     * @return bool
     */
    public function markAsVerified(): bool
    {
        return $this->update(['is_verified' => true]);
    }

    /**
     * Scope to get valid OTPs (not expired and not verified)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->where('is_verified', false);
    }

    /**
     * Scope to get verified OTPs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)
                    ->where('expires_at', '>', now());
    }
} 