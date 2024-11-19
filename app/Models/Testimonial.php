<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function testimonialImage()
    {
        return $this->hasOne(Media::class, 'id', 'testimonial_image');
    }
}