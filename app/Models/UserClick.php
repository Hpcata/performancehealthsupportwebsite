<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClick extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'section_element_id', 'clicked_at', 'ip'];

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
    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    /**
     * Get the user associated with the user click.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the section element associated with the user click.
     */
    public function sectionElement()
    {
        return $this->belongsTo(SectionElement::class);
    }
    
}
