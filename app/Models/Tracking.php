<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['type_id', 'user_id', 'ip', 'details', 'section_element_id', 'user_click_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Add any attributes you want to hide from serialization
    ]; 

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['details' => 'array'];

    /**
     * Get the user associated with the tracking.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the section element associated with the tracking.
     */
    public function sectionElement() {
        return $this->belongsTo(SectionElement::class);
    }

    /**
     * Get the user click associated with the tracking.
     */
    public function userClick() {
        return $this->belongsTo(UserClick::class);
    }

    /**
     * Get the tracking type associated with the tracking.
     */
    public function type() {
        return $this->belongsTo(TrackingType::class);
    }

}   
